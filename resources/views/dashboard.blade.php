@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Título --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
            📊 Panel General
        </h1>
        <span class="text-sm text-gray-500">
            Sistema de Facturación Electrónica
        </span>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        {{-- Documentos del día --}}
        <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm">
            <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                Documentos del día
            </h6>
            <h2 class="mt-2 text-3xl font-semibold text-gray-800">
                {{ $totales['hoy'] }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">Emitidos hoy</p>
        </div>

        {{-- Monto total --}}
        <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm">
            <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                Monto total
            </h6>
            <h2 class="mt-2 text-2xl font-semibold text-gray-800">
                Gs. {{ number_format($totales['total_general'], 0, ',', '.') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Acumulado del sistema
            </p>
        </div>

        {{-- IVA --}}
        <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm">
            <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                Total IVA
            </h6>
            <h2 class="mt-2 text-2xl font-semibold text-gray-800">
                Gs. {{ number_format($totales['total_iva'], 0, ',', '.') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Calculado según documentos
            </p>
        </div>

        {{-- Aprobados --}}
        <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm">
            <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                Aprobados SIFEN
            </h6>
            <h2 class="mt-2 text-3xl font-semibold text-gray-800">
                {{ $totales['total_aprobados'] }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Documentos validados
            </p>
        </div>

    </div>

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Documentos por mes --}}
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-md shadow-sm">
            <div class="px-4 py-3 border-b border-gray-200 text-sm font-semibold text-gray-700">
                📈 Documentos por mes ({{ now()->year }})
            </div>
            <div class="p-4">
                <canvas id="graf_mes" height="120"></canvas>
            </div>
        </div>

        {{-- Estado SIFEN --}}
        <div class="bg-white border border-gray-200 rounded-md shadow-sm">
            <div class="px-4 py-3 border-b border-gray-200 text-sm font-semibold text-gray-700">
                📊 Estado SIFEN
            </div>
            <div class="p-4">
                <canvas id="graf_sifen" height="180"></canvas>
            </div>
        </div>

    </div>

    {{-- Últimos documentos --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden">

        <div class="px-4 py-3 border-b border-gray-200 text-sm font-semibold text-gray-700">
            🧾 Últimos documentos emitidos
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide text-xs">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Fecha</th>
                        <th class="px-4 py-2 text-left">Cliente</th>
                        <th class="px-4 py-2 text-left">Total</th>
                        <th class="px-4 py-2 text-left">Estado SIFEN</th>
                        <th class="px-4 py-2 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white">

                @forelse ($ultimos as $doc)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-gray-700">
                            {{ $doc->id }}
                        </td>

                        <td class="px-4 py-2 text-gray-700">
                            {{ $doc->fecha_emision
                                ? $doc->fecha_emision->format('d/m/Y')
                                : '—'
                            }}
                        </td>

                        <td class="px-4 py-2 text-gray-700">
                            {{ $doc->cliente_nombre ?? '—' }}
                        </td>

                        <td class="px-4 py-2 text-gray-700">
                            Gs. {{ number_format($doc->total_general, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-2">
                            @php
                                $estado = $doc->estado_sifen ?? 'pendiente';
                            @endphp

                            @if($estado === 'aceptado')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                    Aceptado
                                </span>
                            @elseif($estado === 'rechazado')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">
                                    Rechazado
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">
                                    Pendiente
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('documentos.show', $doc->id) }}"
                               class="inline-flex items-center px-3 py-1 rounded-md border border-blue-500 text-blue-600 text-xs hover:bg-blue-50">
                                Ver
                            </a>

                            <a href="{{ route('documentos.pdf', $doc->id) }}"
                               target="_blank"
                               class="inline-flex items-center px-3 py-1 rounded-md border border-red-500 text-red-600 text-xs hover:bg-red-50">
                                PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500 text-sm">
                            No hay documentos recientes
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // === Gráfico documentos por mes ===
    new Chart(document.getElementById('graf_mes'), {
        type: 'line',
        data: {
            labels: {!! json_encode($grafico_meses['labels']) !!},
            datasets: [{
                label: 'Documentos',
                data: {!! json_encode($grafico_meses['data']) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.12)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#2563eb'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: { color: '#374151' }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#6b7280' },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#6b7280' },
                    grid: { color: '#e5e7eb' }
                }
            }
        }
    });

    // === Gráfico Estado SIFEN ===
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
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#374151' }
                }
            }
        }
    });
</script>
@endsection
