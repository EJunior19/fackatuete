<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Services\Sifen\SifenClient;

class DocumentoErpController extends Controller
{
    public function store(Request $request, SifenClient $sifen)
{
    $data = $request->validate([
        'empresa.ruc'           => 'required',
        'empresa.dv'            => 'required',
        'empresa.ambiente'      => 'required|in:test,prod',

        'venta.id_erp'          => 'required|integer',
        'venta.fecha'           => 'required|date_format:Y-m-d H:i:s',
        'venta.tipo_documento'  => 'required',
        'venta.condicion_venta' => 'required|in:contado,credito',
        'venta.moneda'          => 'required',
        'venta.total_general'   => 'required|numeric',
        'venta.total_gravada_10'=> 'nullable|numeric',
        'venta.total_gravada_5' => 'nullable|numeric',
        'venta.total_exenta'    => 'nullable|numeric',

        'cliente.documento'     => 'required',
        'cliente.dv'            => 'nullable|string',
        'cliente.nombre'        => 'required',
        'cliente.direccion'     => 'nullable|string',
        'cliente.telefono'      => 'nullable|string',

        'items'                 => 'required|array|min:1',
        'items.*.codigo'        => 'nullable|string',
        'items.*.descripcion'   => 'required|string',
        'items.*.cantidad'      => 'required|numeric|min:0.01',
        'items.*.precio_unit'   => 'required|numeric|min:0',
        'items.*.subtotal'      => 'required|numeric|min:0',
        'items.*.iva'           => 'nullable|numeric',
    ]);

    // 1) Empresa
    $empresa = Empresa::where('ruc', $data['empresa']['ruc'])
        ->where('dv', $data['empresa']['dv'])
        ->firstOrFail();

    // 2) Cliente
    $cliente = Cliente::firstOrCreate(
        ['ruc' => $data['cliente']['documento']],
        [
            'dv'           => $data['cliente']['dv'] ?? null,
            'razon_social' => $data['cliente']['nombre'],
            'direccion'    => $data['cliente']['direccion'] ?? null,
            'telefono'     => $data['cliente']['telefono'] ?? null,
            'activo'       => true,
        ]
    );

    // 3) Totales desde el ERP
    $totalGravada10 = (float) ($data['venta']['total_gravada_10'] ?? 0);
    $totalGravada5  = (float) ($data['venta']['total_gravada_5']  ?? 0);
    $totalExenta    = (float) ($data['venta']['total_exenta']     ?? 0);
    $totalGeneral   = (float)  $data['venta']['total_general'];

    // 4) Tipo de documento mapeado a SIFEN
    $mapTipo = [
        'FAC' => '1',
        'NCR' => '4',
        'NDE' => '5',
    ];
    $tipoDesdeErp  = $data['venta']['tipo_documento'];
    $tipoParaSifen = $mapTipo[$tipoDesdeErp] ?? $tipoDesdeErp;

    // 5) Crear documento
    $documento = new Documento();
    $documento->empresa_id     = $empresa->id;
    $documento->timbrado_id    = null;
    $documento->tipo_documento = $tipoParaSifen;

    $documento->fecha_emision  = $data['venta']['fecha'];
    $documento->fecha          = substr($data['venta']['fecha'], 0, 10);
    $documento->estado_sifen   = 'pendiente';

    // Cliente
    $documento->receptor_ruc          = $data['cliente']['documento'];
    $documento->receptor_razon_social = $data['cliente']['nombre'];
    $documento->cliente_ruc           = $data['cliente']['documento'];
    $documento->cliente_dv            = $data['cliente']['dv'] ?? null;
    $documento->cliente_nombre        = $data['cliente']['nombre'];
    $documento->cliente_id            = $cliente->id;

    // Totales
    $documento->total_gravada_10 = $totalGravada10;
    $documento->total_gravada_5  = $totalGravada5;
    $documento->total_exenta     = $totalExenta;
    $documento->total_iva        = $totalGravada10 * 0.10 + $totalGravada5 * 0.05;
    $documento->total_general    = $totalGeneral;
    $documento->total_gravado    = $totalGravada10 + $totalGravada5;
    $documento->total            = $totalGeneral;

    // Relación con ERP
    $documento->erp_sale_id      = $data['venta']['id_erp'];
    $documento->lote_id          = null;
    $documento->numero_lote      = null;

    $documento->save();

    // 6) Items
    /*
|--------------------------------------------------------------------------
| 6) Items: guardamos detalle en tabla items
|--------------------------------------------------------------------------
*/
foreach ($data['items'] as $itemData) {

    // Por ahora vamos a determinar el IVA así:
    // - Si la venta tiene gravada 10, ponemos 10
    // - Si tiene gravada 5, ponemos 5
    // - Si no, 0 (exenta)
    $iva = 0;

    if (($totalGravada10 ?? 0) > 0 && ($totalGravada5 ?? 0) == 0 && $totalExenta == 0) {
        $iva = 10;
    } elseif (($totalGravada5 ?? 0) > 0 && ($totalGravada10 ?? 0) == 0 && $totalExenta == 0) {
        $iva = 5;
    } else {
        // si querés respetar lo que viene del ERP cuando ya esté bien:
        // $iva = (int) ($itemData['iva'] ?? 0);
        $iva = (int) ($itemData['iva'] ?? 0);
    }

    $documento->items()->create([
        'producto_id' => null, // si querés relacionar después con un producto interno
        'descripcion' => $itemData['descripcion'],
        'codigo'      => $itemData['codigo'] ?? null,
        'cantidad'    => $itemData['cantidad'],
        'precio_unit' => $itemData['precio_unit'],
        'total'       => $itemData['subtotal'],  // total línea
        'iva'         => $iva,
    ]);
    }

    // 7) Preparar documento (CDC + XML + firma)
    $documento = $sifen->prepararDocumento($documento);

    // 8) Respuesta
    return response()->json([
        'ok'      => true,
        'message' => 'Documento creado y firmado desde ERP',
        'data'    => [
            'id'           => $documento->id,
            'cdc'          => $documento->cdc,
            'xml_generado' => $documento->xml_generado,
            'xml_firmado'  => $documento->xml_firmado,
            'estado_sifen' => $documento->estado_sifen,
            'erp_sale_id'  => $documento->erp_sale_id,
        ],
    ]);
}
}   

