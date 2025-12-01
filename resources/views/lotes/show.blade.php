@extends('layouts.app')

@section('title', 'Lote #'.$lote->id)

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- ENCABEZADO --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            📦 Lote #{{ $lote->id }}
        </h1>

        <a href="{{ route('lotes.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded shadow-sm text-sm">
            ← Volver a Lotes
        </a>
    </div>

    {{-- CARD PRINCIPAL --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6 space-y-6">

        {{-- INFO DEL LOTE --}}
        <div class="space-y-2 text-sm text-gray-700">
            <p>
                <span class="font-semibold text-gray-800">Número de lote:</span>
                {{ $lote->numero_lote }}
            </p>

            <p>
                <span class="font-semibold text-gray-800">Cantidad de documentos:</span>
                {{ $lote->cantidad }}
            </p>

            <p>
                <span class="font-semibold text-gray-800">Estado:</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium
                    @if($lote->estado == 'pendiente')
                        bg-yellow-100 text-yellow-700
                    @elseif($lote->estado == 'enviado')
                        bg-green-100 text-green-700
                    @else
                        bg-red-100 text-red-700
                    @endif">
                    {{ ucfirst($lote->estado) }}
                </span>
            </p>

            <p>
                <span class="font-semibold text-gray-800">Protocolo:</span>
                {{ $lote->protocolo ?? '-' }}
            </p>
        </div>

        {{-- DOCUMENTOS DEL LOTE --}}
        <div class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-800">
                Documentos incluidos
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse text-gray-700">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="border border-gray-200 px-3 py-2 text-left">CDC</th>
                            <th class="border border-gray-200 px-3 py-2 text-left">Tipo</th>
                            <th class="border border-gray-200 px-3 py-2 text-right">Total</th>
                            <th class="border border-gray-200 px-3 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($lote->documentos as $doc)
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-200 px-3 py-2">
                                    {{ $doc->cdc }}
                                </td>
                                <td class="border border-gray-200 px-3 py-2">
                                    {{ $doc->tipo_documento }}
                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-right">
                                    Gs. {{ number_format($doc->total, 0, '.', '.') }}
                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-center">
                                    <a href="{{ route('documentos.show', $doc->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-semibold text-xs">
                                        Ver documento
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-sm text-gray-500">
                                    Este lote no contiene documentos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection
