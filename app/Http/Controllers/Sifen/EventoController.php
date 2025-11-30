<?php

namespace App\Http\Controllers\Sifen;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::orderBy('id', 'DESC')->paginate(30);
        return view('eventos.index', compact('eventos'));
    }

    public function show($id)
    {
        $evento = Evento::findOrFail($id);
        return view('eventos.show', compact('evento'));
    }
}
