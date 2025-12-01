@extends('layouts.app')

@section('title', 'Lotes SIFEN')

@section('content')

<div class="space-y-6">

    {{-- Título --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            📤 Lotes enviados a SIFEN
        </h1>
    </div>

    {{-- Contenedor principal --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-4 space-y-4">

        <p class="text-sm text-gray-500">
            Visualizá los lotes generados y enviados al SIFEN con su estado actual.
        </p>

        <div class="text-gray-700 mb-4">
            Total lotes: {{ $lotes->total() }}
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-md">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-600">
                        <th class="px-3 py-2 text-left border-b border-gray-200">ID</th>
                        <th class="px-3 py-2 text-left border-b border-gray-200">Número de lote</th>
                        <th class="px-3 py-2 text-left border-b border-gray-200">Cantidad</th>
                        <th class="px-3 py-2 text-left border-b border-gray-200">Estado</th>
                        <th class="px-3 py-2 text-right border-b border-gray-200">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($lotes as $lote)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-gray-800">
                                {{ $lote->id }}
                            </td>
                            <td class="px-3 py-2 text-gray-800">
                                {{ $lote->numero_lote }}
                            </td>
                            <td class="px-3 py-2 text-gray-800">
                                {{ $lote->cantidad }}
                            </td>
                            <td class="px-3 py-2 text-gray-800">
                                {{ $lote->estado }}
                            </td>
                            <td class="px-3 py-2 text-right space-x-2">
                                <a href="{{ route('lotes.show', $lote) }}"
                                   class="inline-flex items-center px-2 py-1 rounded-md text-xs
                                          bg-blue-600 text-white hover:bg-blue-700">
                                    Ver
                                </a>

                                <a href="{{ route('lotes.xml', $lote) }}"
                                   class="inline-flex items-center px-2 py-1 rounded-md text-xs
                                          bg-purple-600 text-white hover:bg-purple-700">
                                    Ver XML
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-sm text-gray-500">
                                No hay lotes registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{-- {{ $lotes->links() }} --}}
        </div>

    </div>

</div>

@endsection
