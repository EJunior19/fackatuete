<?php

namespace App\Http\Controllers\Sifen;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Services\Lote\LoteService;
use App\Services\Sifen\SifenClient;

class LotesController extends Controller
{
   public function index()
    {
       
        $lotes = Lote::orderBy('id', 'DESC')->paginate(20);
        return view('lotes.index', compact('lotes'));
    }

    public function show($id)
    {
        $lote = Lote::with('documentos')->findOrFail($id);
        return view('lotes.show', compact('lote'));
    }

    public function enviar($id)
    {
        $lote = Lote::findOrFail($id);

        $client    = app(SifenClient::class);
        $respuesta = $client->enviarLote($lote);

        return back()->with('success', 'Lote enviado correctamente');
    }

    public function verXml(Lote $lote, LoteService $loteService)
{
    $lote->load('documentos');

    $xmlEnviado   = $lote->xml_enviado;
    $xmlRespuesta = $lote->xml_respuesta ?? $lote->respuesta ?? null;

    return view('lotes.xml', [
        'lote'         => $lote,
        'xmlEnviado'   => $xmlEnviado,
        'xmlRespuesta' => $xmlRespuesta,
    ]);
}


}
