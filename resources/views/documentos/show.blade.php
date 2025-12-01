@extends('layouts.app')

@section('title', 'Detalle del documento')

@section('content')

<div class="space-y-6 max-w-6xl mx-auto">

    {{-- Encabezado + acciones --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                🧾 Detalle del documento
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                CDC / Número: <span class="font-medium text-gray-800">{{ $documento->numero ?? '—' }}</span>
            </p>
            <p class="text-sm text-gray-500">
                Cliente: <span class="font-medium text-gray-800">{{ $documento->cliente_nombre }}</span>
            </p>
            <p class="text-sm text-gray-500">
                Fecha de emisión: <span class="font-medium text-gray-800">{{ $documento->fecha_emision }}</span>
            </p>
            <p class="mt-1 text-sm text-gray-500 flex items-center gap-2">
                Estado SIFEN:
                @php
                    $estado = $documento->estado_sifen;
                @endphp
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                    @if($estado === 'aceptado')
                        bg-green-100 text-green-700
                    @elseif($estado === 'pendiente')
                        bg-yellow-100 text-yellow-700
                    @elseif($estado === 'rechazado')
                        bg-red-100 text-red-700
                    @else
                        bg-gray-100 text-gray-700
                    @endif">
                    {{ ucfirst($estado ?? '—') }}
                </span>
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('documentos.pdf',$documento->id) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border border-red-500 text-red-600 hover:bg-red-50">
                Descargar PDF
            </a>

            <a href="{{ route('documentos.firmar',$documento->id) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border border-blue-600 text-white bg-blue-600 hover:bg-blue-700">
                Enviar a SIFEN
            </a>
        </div>
    </div>

    {{-- Card principal --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6 space-y-6">

        {{-- Ítems --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                📦 Ítems del documento
            </h3>

            <div class="overflow-x-auto border border-gray-200 rounded-md">
                <table class="min-w-full text-xs sm:text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-gray-600 uppercase tracking-wide">
                            <th class="px-3 py-2 text-left border-b border-gray-200">Descripción</th>
                            <th class="px-3 py-2 text-right border-b border-gray-200">Cant.</th>
                            <th class="px-3 py-2 text-right border-b border-gray-200">Precio</th>
                            <th class="px-3 py-2 text-center border-b border-gray-200">IVA</th>
                            <th class="px-3 py-2 text-right border-b border-gray-200">Subtotal</th>
                            <th class="px-3 py-2 text-right border-b border-gray-200">Total</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($documento->items as $item)
                            @php
                                $subtotal = $item->cantidad * $item->precio_unit;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 text-gray-800">{{ $item->descripcion }}</td>
                                <td class="px-3 py-2 text-right text-gray-800">
                                    {{ number_format($item->cantidad, 2, ',', '.') }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-800">
                                    {{ number_format($item->precio_unit, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2 text-center text-gray-800">{{ $item->iva }}%</td>
                                <td class="px-3 py-2 text-right text-gray-800">
                                    {{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-800">
                                    {{ number_format($item->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach

                        @if($documento->items->count() === 0)
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                                    No hay ítems cargados para este documento.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Totales --}}
        @php
            $gravada10 = $documento->items->where('iva', 10)->sum('total');
            $gravada5  = $documento->items->where('iva', 5)->sum('total');
            $exenta    = $documento->items->where('iva', 0)->sum('total');
            $totalDoc  = $documento->items->sum('total');
        @endphp

        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                💰 Totales
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div class="bg-gray-50 border border-gray-200 rounded-md px-3 py-2">
                    <p class="text-gray-500 text-xs uppercase">Gravada 10%</p>
                    <p class="text-gray-800 font-medium">
                        {{ number_format($gravada10, 0, ',', '.') }} Gs.
                    </p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-md px-3 py-2">
                    <p class="text-gray-500 text-xs uppercase">Gravada 5%</p>
                    <p class="text-gray-800 font-medium">
                        {{ number_format($gravada5, 0, ',', '.') }} Gs.
                    </p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-md px-3 py-2">
                    <p class="text-gray-500 text-xs uppercase">Exenta</p>
                    <p class="text-gray-800 font-medium">
                        {{ number_format($exenta, 0, ',', '.') }} Gs.
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-md px-3 py-2">
                    <p class="text-blue-700 text-xs uppercase">Total documento</p>
                    <p class="text-blue-800 font-semibold text-lg">
                        {{ number_format($totalDoc, 0, ',', '.') }} Gs.
                    </p>
                </div>
            </div>
        </div>

        {{-- XML Firmado --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-2">
                🧩 XML firmado
            </h3>

            <textarea
                readonly
                class="w-full h-56 mt-1 rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-xs font-mono text-gray-800 focus:outline-none">
{{ $documento->xml_firmado }}
            </textarea>
        </div>

        {{-- Volver --}}
        <div class="pt-2">
            <a href="{{ route('documentos.index') }}"
               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                ← Volver a la lista
            </a>
        </div>

    </div>

</div>

@endsection
