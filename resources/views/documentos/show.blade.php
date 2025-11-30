<x-app-layout>

    <!-- Header -->
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-100">
            🧾 Detalle del Documento
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto mt-6 bg-gray-800 text-gray-100 rounded-xl shadow-lg border border-gray-700">

        <div class="p-6 space-y-6">

            <!-- ENCABEZADO DEL DOCUMENTO -->
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold">
                        CDC: {{ $documento->numero ?? '—' }}
                    </h2>
                    <p class="text-gray-300 mt-1"><strong>Cliente:</strong> {{ $documento->cliente_nombre }}</p>
                    <p class="text-gray-300"><strong>Fecha de Emisión:</strong> {{ $documento->fecha_emision }}</p>
                    <p class="text-gray-300">
                        <strong>Estado SIFEN:</strong> 
                        <span class="
                            px-2 py-1 rounded text-xs
                            @if($documento->estado_sifen == 'aceptado') bg-green-700
                            @elseif($documento->estado_sifen == 'pendiente') bg-yellow-600 text-black
                            @else bg-red-700
                            @endif">
                            {{ ucfirst($documento->estado_sifen) }}
                        </span>
                    </p>
                </div>

                <!-- Acciones -->
                <div class="flex gap-3">
                    <a href="{{ route('documentos.pdf',$documento->id) }}"
                       target="_blank"
                       class="bg-red-600 hover:bg-red-700 px-4 py-2 text-white rounded-lg transition">
                        Descargar PDF
                    </a>

                    <a href="{{ route('documentos.firmar',$documento->id) }}"
                       class="bg-green-600 hover:bg-green-700 px-4 py-2 text-white rounded-lg transition">
                        Enviar a SIFEN
                    </a>
                </div>
            </div>

            <hr class="border-gray-700">

            <!-- ITEMS -->
            <div>
                <h3 class="text-lg font-semibold mb-3">📦 Ítems del Documento</h3>

                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-700 rounded-lg text-sm">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="border border-gray-700 px-3 py-2 text-left">Descripción</th>
                                <th class="border border-gray-700 px-3 py-2 text-right">Cant.</th>
                                <th class="border border-gray-700 px-3 py-2 text-right">Precio</th>
                                <th class="border border-gray-700 px-3 py-2 text-center">IVA</th>
                                <th class="border border-gray-700 px-3 py-2 text-right">Subtotal</th>
                                <th class="border border-gray-700 px-3 py-2 text-right">Total</th>
                            </tr>
                        </thead>

                        <tbody class="bg-gray-800">
                            @foreach($documento->items as $item)
                                @php
                                    $subtotal = $item->cantidad * $item->precio_unit;
                                @endphp
                                <tr class="hover:bg-gray-700">
                                    <td class="border border-gray-700 px-3 py-2">{{ $item->descripcion }}</td>
                                    <td class="border border-gray-700 px-3 py-2 text-right">{{ number_format($item->cantidad, 2) }}</td>
                                    <td class="border border-gray-700 px-3 py-2 text-right">{{ number_format($item->precio_unit, 0) }}</td>
                                    <td class="border border-gray-700 px-3 py-2 text-center">{{ $item->iva }}%</td>
                                    <td class="border border-gray-700 px-3 py-2 text-right">{{ number_format($subtotal, 0) }}</td>
                                    <td class="border border-gray-700 px-3 py-2 text-right">{{ number_format($item->total, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <hr class="border-gray-700">

            <!-- TOTALES -->
            @php
                $gravada10 = $documento->items->where('iva', 10)->sum('total');
                $gravada5  = $documento->items->where('iva', 5)->sum('total');
                $exenta    = $documento->items->where('iva', 0)->sum('total');
            @endphp

            <div>
                <h3 class="text-lg font-semibold mb-2">💰 Totales</h3>

                <div class="space-y-1 text-gray-200">
                    <p><strong>Gravada 10%:</strong> {{ number_format($gravada10, 0) }} Gs.</p>
                    <p><strong>Gravada 5%:</strong> {{ number_format($gravada5, 0) }} Gs.</p>
                    <p><strong>Exenta:</strong> {{ number_format($exenta, 0) }} Gs.</p>
                </div>

                <p class="text-2xl font-bold mt-3 text-white">
                    TOTAL: {{ number_format($documento->items->sum('total'), 0) }} Gs.
                </p>
            </div>

            <hr class="border-gray-700">

            <!-- XML FIRMADO -->
            <div>
                <h3 class="text-lg font-semibold">🧩 XML Firmado</h3>

                <textarea readonly 
                    class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 text-gray-300 h-56 mt-2">
{{ $documento->xml_firmado }}
                </textarea>
            </div>

            <div class="mt-4">
                <a href="{{ route('documentos.index') }}" 
                   class="text-blue-400 hover:text-blue-300 transition">
                    ← Volver a la lista
                </a>
            </div>

        </div>

    </div>

</x-app-layout>
