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
}
