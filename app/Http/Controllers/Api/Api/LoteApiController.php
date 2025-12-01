<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Documento;
use App\Services\Sifen\SifenClient;
use App\Services\Lote\LoteService;
use Illuminate\Http\Request;

class LoteApiController extends Controller
{
    public function __construct(
        protected SifenClient $sifen,
        protected LoteService $loteService,
    ) {}

    // GET /api/v1/lotes
    public function index()
    {
        $lotes = Lote::latest()->paginate(20);

        return response()->json([
            'ok'    => true,
            'data'  => $lotes,
        ]);
    }

    // POST /api/v1/lotes
    // body: { "documentos": [1,2,3] }
    public function store(Request $request)
    {
        $data = $request->validate([
            'documentos'   => 'required|array|min:1',
            'documentos.*' => 'integer|exists:documentos,id',
        ]);

        // Crear lote
        $lote = new Lote();
        $lote->numero_lote = $this->loteService->generarNumeroLote();
        $lote->cantidad    = count($data['documentos']);
        $lote->estado      = 'pendiente';
        $lote->save();

        // Asociar documentos al lote
        Documento::whereIn('id', $data['documentos'])
            ->update(['lote_id' => $lote->id]);

        return response()->json([
            'ok'   => true,
            'data' => $lote,
        ], 201);
    }

    // GET /api/v1/lotes/{lote}
    public function show(Lote $lote)
    {
        $lote->load('documentos');

        return response()->json([
            'ok'   => true,
            'data' => $lote,
        ]);
    }

    // POST /api/v1/lotes/{lote}/enviar
    public function enviar(Lote $lote)
    {
        $res = $this->sifen->enviarLote($lote);

        return response()->json($res);
    }

    // GET /api/v1/lotes/{lote}/consultar
    public function consultar(Lote $lote)
    {
        $res = $this->sifen->consultarLote($lote->numero_lote);

        return response()->json($res);
    }
}
