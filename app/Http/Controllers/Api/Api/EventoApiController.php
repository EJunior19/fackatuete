<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoApiController extends Controller
{
    // GET /api/v1/eventos
    public function index(Request $request)
    {
        $query = Evento::query()->latest();

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('codigo')) {
            $query->where('codigo', $request->codigo);
        }

        $eventos = $query->paginate(50);

        return response()->json([
            'ok'    => true,
            'data'  => $eventos,
        ]);
    }

    // GET /api/v1/eventos/{evento}
    public function show(Evento $evento)
    {
        return response()->json([
            'ok'   => true,
            'data' => $evento,
        ]);
    }
}
