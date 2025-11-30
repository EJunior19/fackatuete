@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Título --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            📊 Dashboard General
        </h1>
        <span class="text-gray-400">Sistema de Facturación Electrónica</span>
    </div>

    {{-- Tarjetas estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-lg transition">
            <h6 class="text-gray-400">Documentos del día</h6>
            <h2 class="text-3xl font-bold text-blue-400">{{ $totales['hoy'] }}</h2>
            <p class="text-gray-500 text-sm mt-1">Emitidos hoy</p>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-lg transition">
            <h6 class="text-gray-400">Monto Total</h6>
            <h2 class="text-3xl font-bold text-green-400">
                Gs. {{ number_format($totales['total_general'], 0, ',', '.') }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Acumulado del sistema</p>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-lg transition">
            <h6 class="text-gray-400">IVA Total</h6>
            <h2 class="text-3xl font-bold text-yellow-400">
                Gs. {{ number_format($totales['total_iva'], 0, ',', '.') }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Calculado según documentos</p>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-lg transition">
            <h6 class="text-gray-400">Aprobados SIFEN</h6>
            <h2 class="text-3xl font-bold text-green-500">{{ $totales['total_aprobados'] }}</h2>
            <p class="text-gray-500 text-sm mt-1">Documentos validados</p>
        </div>

    </div>

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <div class="lg:col-span-2 bg-gray-800 border border-gray-700 rounded-lg shadow">
            <div class="px-5 py-3 border-b border-gray-700 font-bold text-gray-200">
                📈 Documentos por Mes
            </div>
            <div class="p-5">
                <canvas id="graf_mes" height="120"></canvas>
            </div>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">
            <div class="px-5 py-3 border-b border-gray-700 font-bold text-gray-200">
                📊 Estado SIFEN
            </div>
            <div class="p-5">
                <canvas id="graf_sifen" height="180"></canvas>
            </div>
        </div>

    </div>

    {{-- Tabla últimos documentos --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow overflow-hidden">

        <div class="px-5 py-3 border-b border-gray-700 font-bold text-gray-200">
            🧾 Últimos Documentos Emitidos
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-700 text-gray-300 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Cliente</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Estado SIFEN</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-700">

                    @foreach ($ultimos as $doc)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3 text-gray-300">{{ $doc->id }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $doc->fecha }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $doc->cliente_nombre }}</td>
                            <td class="px-4 py-3 text-gray-300">
                                Gs. {{ number_format($doc->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">

                                @if($doc->estado_sifen == 'aceptado')
                                    <span class="px-2 py-1 rounded bg-green-600 text-white text-xs">Aceptado</span>
                                @elseif($doc->estado_sifen == 'pendiente')
                                    <span class="px-2 py-1 rounded bg-yellow-500 text-black text-xs">Pendiente</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-red-600 text-white text-xs">Rechazado</span>
                                @endif

                            </td>

                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('documentos.show', $doc->id) }}"
                                   class="inline-block px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                                    Ver
                                </a>

                                <a href="{{ route('documentos.pdf', $doc->id) }}" target="_blank"
                                   class="inline-block px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                    PDF
                                </a>
                            </td>

                        </tr>
                    @endforeach

                    @if($ultimos->count() == 0)
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">
                                No hay documentos recientes
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // === Gráfico de documentos por mes ===
    new Chart(document.getElementById('graf_mes'), {
        type: 'line',
        data: {
            labels: {!! json_encode($grafico_meses['labels']) !!},
            datasets: [{
                label: 'Documentos',
                data: {!! json_encode($grafico_meses['data']) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.25)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            plugins: { legend: { labels: { color: '#ddd' } } },
            scales: {
                x: { ticks: { color: '#bbb' } },
                y: { ticks: { color: '#bbb' } },
            }
        }
    });

    // === Gráfico SIFEN ===
    new Chart(document.getElementById('graf_sifen'), {
        type: 'doughnut',
        data: {
            labels: ['Aceptado', 'Pendiente', 'Rechazado'],
            datasets: [{
                data: [
                    {{ $sifen['aceptado'] }},
                    {{ $sifen['pendiente'] }},
                    {{ $sifen['rechazado'] }}
                ],
                backgroundColor: ['#22c55e','#eab308','#ef4444']
            }]
        },
        options: {
            plugins: { legend: { labels: { color: '#ddd' } } }
        }
    });
</script>
@endsection
