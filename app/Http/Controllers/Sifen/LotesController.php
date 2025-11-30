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

        $client = app(SifenClient::class);
        $respuesta = $client->enviarLote($lote);

        return back()->with('success', 'Lote enviado correctamente');
    }

    public function consultar($id)
    {
        $lote = Lote::findOrFail($id);

        $client = app(SifenClient::class);
        $respuesta = $client->consultarLote($lote);

        return back()->with('success', 'Consulta realizada');
    }

    public function xml($id)
    {
        $lote = Lote::findOrFail($id);

        return view('lotes.xml', [
            'xml' => $lote->xml_enviado,
            'xml_recibido' => $lote->xml_respuesta
        ]);
    }
}
