<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;

class DashboardController extends Controller
{
    public function index()
    {
        // Totales generales
        $totales = [
            'hoy'             => Documento::whereDate('created_at', now())->count(),
            'total_iva'       => Documento::sum('total_iva') ?? 0,
            'total_general'   => Documento::sum('total') ?? 0,
            'total_aprobados' => Documento::where('estado_sifen', 'aceptado')->count(),
        ];

        // Últimos documentos
        $ultimos = Documento::orderBy('id', 'desc')
                            ->take(10)
                            ->get();

        // Estado SIFEN
        $sifen = [
            'aceptado'  => Documento::where('estado_sifen', 'aceptado')->count(),
            'pendiente' => Documento::where('estado_sifen', 'pendiente')->count(),
            'rechazado' => Documento::where('estado_sifen', 'rechazado')->count(),
        ];

        // Gráfico mensual
        $grafico_meses = [
            'labels' => ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Set','Oct','Nov','Dic'],
            'data'   => Documento::selectRaw('EXTRACT(MONTH FROM created_at) as mes, COUNT(*) as total')
                                 ->groupBy('mes')
                                 ->orderBy('mes')
                                 ->pluck('total')
                                 ->toArray(),
        ];

        return view('dashboard', compact(
            'totales',
            'ultimos',
            'sifen',
            'grafico_meses'
        ));
    }
}
