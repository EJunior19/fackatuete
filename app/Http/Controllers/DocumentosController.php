<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Numeracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\Sifen\SifenClient;


// 👇 servicios SIFEN / Firma
use App\Services\Sifen\XMLBuilder;
use App\Services\Firma\XmlSigner;

use Barryvdh\DomPDF\Facade\Pdf;

class DocumentosController extends Controller
{
    public function index()
    {
        $documentos = Documento::orderByDesc('id')->paginate(15);
        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        return view('documentos.create');
    }

    public function store(Request $request)
    {
        // 1) Empresa actual (por ahora fija 1 si no hay multi-empresa)
        $empresaId = auth()->user()->empresa_id ?? 1;

        // 2) Numeración activa (solo para obtener timbrado_id)
        $numeracion = Numeracion::where('empresa_id', $empresaId)
            ->where('tipo_documento', $request->tipo_documento)
            ->where('activo', true)
            ->firstOrFail();

        // 3) Separar RUC / DV
        $rucCompleto = trim($request->cliente_ruc);
        $rucSolo = $rucCompleto;
        $dv = null;

        if (str_contains($rucCompleto, '-')) {
            [$rucSolo, $dv] = explode('-', $rucCompleto);
        }

        // 4) Crear documento (la numeración la pondrá el boot())
        $documento = Documento::create([
            'empresa_id'       => $empresaId,
            'timbrado_id'      => $numeracion->timbrado_id,
            'tipo_documento'   => $request->tipo_documento,
            'fecha_emision'    => $request->fecha_emision,
            'estado_sifen'     => 'pendiente',

            'cliente_ruc'      => $rucSolo,
            'cliente_dv'       => $dv,
            'cliente_nombre'   => $request->cliente_nombre,

            // Inicializamos totales en 0
            'total_gravada_10' => 0,
            'total_gravada_5'  => 0,
            'total_exenta'     => 0,
            'total_iva'        => 0,
            'total_general'    => 0,
        ]);

        // 5) Crear items y calcular totales
        $grav10       = 0;
        $grav5        = 0;
        $exenta       = 0;
        $ivaTotal     = 0;
        $totalGeneral = 0;

        foreach ($request->items ?? [] as $it) {
            if (empty($it['descripcion'])) {
                continue;
            }

            $cantidad     = (float)($it['cantidad'] ?? 0);
            $precioSinIva = (float)($it['precio'] ?? 0);   // PRECIO SIN IVA
            $ivaPorc      = (int)($it['iva'] ?? 10);       // 10, 5 o 0

            if ($cantidad <= 0 || $precioSinIva < 0) {
                continue;
            }

            // Base imponible
            $subtotal = $cantidad * $precioSinIva;

            // IVA según porcentaje
            if ($ivaPorc === 10) {
                $ivaMonto = round($subtotal * 0.10);
                $grav10  += $subtotal;
            } elseif ($ivaPorc === 5) {
                $ivaMonto = round($subtotal * 0.05);
                $grav5   += $subtotal;
            } else {
                $ivaMonto = 0;
                $exenta  += $subtotal;
            }

            $totalItemConIva = $subtotal + $ivaMonto;

            $ivaTotal     += $ivaMonto;
            $totalGeneral += $totalItemConIva;

            $documento->items()->create([
                'descripcion' => $it['descripcion'],
                'cantidad'    => $cantidad,
                'precio_unit' => $precioSinIva,
                'total'       => $totalItemConIva,
                'iva'         => $ivaPorc,
            ]);
        }

        // 6) Guardar totales en el documento
        $documento->total_gravada_10 = $grav10;
        $documento->total_gravada_5  = $grav5;
        $documento->total_exenta     = $exenta;
        $documento->total_iva        = $ivaTotal;
        $documento->total_general    = $totalGeneral;
        $documento->save();

        return redirect()->route('documentos.show', $documento->id);
    }

    public function show($id)
    {
        $documento = Documento::with('items')->findOrFail($id);
        return view('documentos.show', compact('documento'));
    }

    public function edit($id)
    {
        $documento = Documento::with('items')->findOrFail($id);
        return view('documentos.edit', compact('documento'));
    }

    public function update(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);
        $documento->update($request->all());

        return redirect()->route('documentos.show', $id)
            ->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $documento = Documento::findOrFail($id);
        $documento->delete();

        return redirect()->route('documentos.index')
            ->with('success', 'Documento eliminado.');
    }

     public function firmar($id, SifenClient $sifen)
    {
        $documento = Documento::with(['empresa', 'items', 'cliente'])->findOrFail($id);

        try {
            // Si el documento todavía no tiene CDC o XML firmado,
            // lo preparamos con el flujo nuevo
            if (empty($documento->cdc) || empty($documento->xml_firmado)) {
                $documento = $sifen->prepararDocumento($documento);
            }

            // 👇 Aquí, en el futuro, vas a llamar al cliente real de SIFEN
            // por ejemplo: $sifen->enviarASifen($documento);
            // Por ahora solo devolvemos OK de la firma/preparación.

            return redirect()
            ->route('documentos.show', $documento->id)
            ->with('success', 'Documento preparado y firmado correctamente.');
    } catch (\Throwable $e) {
        report($e);

        return redirect()
            ->route('documentos.show', $documento->id)
            ->with('error', 'Error al generar o firmar el XML: '.$e->getMessage());
    
        }
    }

    public function pdf($id)
    {
        // Traemos el documento con todo lo necesario
        $documento = Documento::with(['empresa', 'items', 'timbrado'])->findOrFail($id);

        // Vista Blade que va a renderizar el PDF
        $pdf = Pdf::loadView('documentos.pdf', [
            'documento' => $documento,
        ])->setPaper('a4', 'portrait');

        // Nombre del archivo
        $numero = $documento->numero ?? str_pad($documento->id, 7, '0', STR_PAD_LEFT);
        $fileName = "Factura_{$numero}.pdf";

        return $pdf->download($fileName);
        // si querés que se abra en el navegador:
        // return $pdf->stream($fileName);
    }
}
