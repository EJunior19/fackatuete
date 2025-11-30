<?php

namespace App\Http\Controllers\Sifen;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Lote;
use App\Services\Sifen\SifenClient;
use Illuminate\Http\Request;

class SifenController extends Controller
{
    public function __construct(
        protected SifenClient $sifen,
    ) {}

    public function test()
    {
        return response()->json([
            'status'  => 'OK',
            'message' => 'FacKatuete conectado y funcionando.',
        ]);
    }

    /**
     * Demo: crear un documento de prueba y prepararlo (CDC + XML + firma)
     */
    public function demoPrepararDocumento()
    {
        $empresa = Empresa::first();

        if (!$empresa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No existe empresa cargada aún.',
            ], 400);
        }

        $documento = Documento::create([
            'empresa_id'     => $empresa->id,
            'tipo_documento' => 'FE',
            'fecha_emision'  => now(),
            'total'          => 100000,
        ]);

        $this->sifen->prepararDocumento($documento);

        return response()->json([
            'status'     => 'OK',
            'documento'  => $documento->fresh(),
        ]);
    }

    /**
     * Demo: crear un lote vacío y enviarlo “fake” a SIFEN
     */
    public function demoEnviarLote()
    {
        $empresa = Empresa::first();

        if (!$empresa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No existe empresa cargada aún.',
            ], 400);
        }

        $lote = Lote::create([
            'empresa_id'  => $empresa->id,
            'numero_lote' => now()->format('YmdHis'),
            'cantidad'    => 0,
            'estado'      => 'pendiente',
        ]);

        $result = $this->sifen->enviarLote($lote);

        return response()->json([
            'status'  => $result['ok'] ? 'OK' : 'ERROR',
            'data'    => $result,
        ]);
    }
}
