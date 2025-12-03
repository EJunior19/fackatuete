<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Services\Sifen\SifenClient;
use Illuminate\Http\Request;

class DocumentoApiController extends Controller
{
    public function __construct(
        protected SifenClient $sifen
    ) {
    }

    // GET /api/v1/documentos
    public function index(Request $request)
    {
        $docs = Documento::query()
            ->latest()
            ->paginate(20);

        return response()->json($docs);
    }

    // POST /api/v1/documentos
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'      => 'required|integer|exists:clientes,id',
            'tipo_documento'  => 'required|string|max:5', // FE, ND, NC...
            'fecha'           => 'required|date',
            'total'           => 'required|numeric|min:0',
            // acá podés ir agregando más campos necesarios
        ]);

        // Crear documento "crudo" (sin CDC ni firma)
        $doc = Documento::create($data);

        return response()->json([
            'ok'   => true,
            'data' => $doc,
        ], 201);
    }

    // GET /api/v1/documentos/{doc}
    public function show(Documento $doc)
    {
        return response()->json([
            'ok'   => true,
            'data' => $doc,
        ]);
    }

    // POST /api/v1/documentos/{doc}/firmar
    public function firmar(Documento $doc)
    {
        $this->sifen->prepararDocumento($doc);

        return response()->json([
            'ok'   => true,
            'data' => [
                'id'          => $doc->id,
                'cdc'         => $doc->cdc,
                'xml_generado'=> $doc->xml_generado,
                'xml_firmado' => $doc->xml_firmado,
            ],
        ]);
    }

    // GET /api/v1/documentos/{doc}/cdc
    public function consultarCdc(Documento $doc)
    {
        if (!$doc->cdc) {
            return response()->json([
                'ok'    => false,
                'error' => 'El documento no tiene CDC todavía.',
            ], 400);
        }

        $res = $this->sifen->consultarCdc($doc->cdc);

        return response()->json($res);
    }
     /**
     * POST /api/v1/documentos/desde-erp
     *
     * Recibe una venta del ERP y crea el Documento + Items,
     * firma el XML y devuelve datos para sincronizar.
     */
    public function desdeErp(Request $request)
    {
        $data = $request->validate([
            'empresa.ruc'              => 'required|string|max:20',
            'empresa.dv'               => 'nullable|string|max:5',
            'empresa.ambiente'         => 'required|string',

            'venta.id_erp'             => 'required|integer',
            'venta.fecha'              => 'required|date_format:Y-m-d H:i:s',
            'venta.tipo_documento'     => 'required|string|max:5', // FAC, NDC, NDC
            'venta.condicion_venta'    => 'required|string',       // contado/credito
            'venta.moneda'             => 'required|string|max:3',
            'venta.total_general'      => 'required|numeric',
            'venta.total_gravada_10'   => 'nullable|numeric',
            'venta.total_gravada_5'    => 'nullable|numeric',
            'venta.total_exenta'       => 'nullable|numeric',

            'cliente.documento'        => 'required|string|max:20',
            'cliente.dv'               => 'nullable|string|max:5',
            'cliente.nombre'           => 'required|string|max:200',
            'cliente.direccion'        => 'nullable|string|max:200',
            'cliente.telefono'         => 'nullable|string|max:50',

            'items'                    => 'required|array|min:1',
            'items.*.codigo'           => 'nullable|string|max:50',
            'items.*.descripcion'      => 'required|string|max:255',
            'items.*.cantidad'         => 'required|numeric|min:0.0001',
            'items.*.precio_unit'      => 'required|numeric|min:0',
            'items.*.subtotal'         => 'required|numeric|min:0',
            'items.*.iva'              => 'required|integer|in:0,5,10',
        ]);

        // 1) Buscar empresa por RUC (por ahora asumimos 1)
        $empresa = Empresa::where('ruc', $data['empresa']['ruc'])->firstOrFail();

        // 2) Opcional: buscar/crear cliente interno por RUC
        $clienteRuc = $data['cliente']['documento'];
        $clienteDv  = $data['cliente']['dv'] ?? null;

        $cliente = Cliente::firstOrCreate(
            [
                'ruc' => $clienteRuc,
                'dv'  => $clienteDv,
            ],
            [
                'nombre'    => $data['cliente']['nombre'],
                'direccion' => $data['cliente']['direccion'] ?? null,
                'telefono'  => $data['cliente']['telefono'] ?? null,
            ]
        );

        // 3) Crear Documento (el boot de Documento se encarga de numeración)
        $doc = new Documento();
        $doc->empresa_id       = $empresa->id;
        $doc->timbrado_id      = $empresa->timbrado_activo_id ?? null; // ajustá a tu modelo
        $doc->tipo_documento   = $data['venta']['tipo_documento'];
        $doc->fecha_emision    = $data['venta']['fecha'];
        $doc->estado_sifen     = 'pendiente';

        $doc->cliente_id       = $cliente->id;
        $doc->cliente_ruc      = $clienteRuc;
        $doc->cliente_dv       = $clienteDv;
        $doc->cliente_nombre   = $cliente->nombre;

        $doc->total_gravada_10 = $data['venta']['total_gravada_10'] ?? 0;
        $doc->total_gravada_5  = $data['venta']['total_gravada_5'] ?? 0;
        $doc->total_exenta     = $data['venta']['total_exenta'] ?? 0;
        $doc->total_general    = $data['venta']['total_general'];
        $doc->total_iva        = (
            ($doc->total_gravada_10 * 0.10) +
            ($doc->total_gravada_5 * 0.05)
        );

        // opcional: guardar id de la venta en el ERP
        if ($doc->isFillable('erp_sale_id')) {
            $doc->erp_sale_id = $data['venta']['id_erp'];
        }

        $doc->save();

        // 4) Crear items
        foreach ($data['items'] as $it) {
            $cantidad    = (float) $it['cantidad'];
            $precio      = (float) $it['precio_unit'];
            $ivaPorc     = (int) $it['iva'];
            $subtotal    = $cantidad * $precio;
            $ivaMonto    = 0;

            if ($ivaPorc === 10) {
                $ivaMonto = round($subtotal * 0.10);
            } elseif ($ivaPorc === 5) {
                $ivaMonto = round($subtotal * 0.05);
            }

            $doc->items()->create([
                'descripcion' => $it['descripcion'],
                'codigo'      => $it['codigo'] ?? null,
                'cantidad'    => $cantidad,
                'precio_unit' => $precio,
                'total'       => $subtotal + $ivaMonto,
                'iva'         => $ivaPorc,
            ]);
        }

        // 5) Preparar + firmar el XML
        $this->sifen->prepararDocumento($doc);

        // 6) Devolver datos para el ERP
        return response()->json([
            'ok'   => true,
            'data' => [
                'id'               => $doc->id,
                'cdc'              => $doc->cdc,
                'estado_sifen'     => $doc->estado_sifen ?? 'pendiente',
                'xml_firmado'      => $doc->xml_firmado,

                // 🔥 numeración que el ERP necesita
                'establecimiento'  => $doc->establecimiento,
                'punto_expedicion' => $doc->punto_expedicion,
                'numero'           => $doc->numero,

                'total_general'    => (float) $doc->total_general,
                'erp_sale_id'      => $doc->erp_sale_id ?? null,
            ],
        ], 201);
    }
}
