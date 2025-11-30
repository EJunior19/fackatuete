<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-100">
            Detalles del Lote #{{ $lote->id }}
        </h2>
    </x-slot>

    <div class="bg-gray-900 p-6 rounded-xl shadow-lg border border-gray-700 max-w-4xl mx-auto">

        <!-- Información del lote -->
        <h3 class="text-2xl font-bold text-gray-100 mb-4">Información del lote</h3>

        <div class="space-y-2 text-gray-300">
            <p><span class="font-semibold text-gray-200">Número de lote:</span> {{ $lote->numero_lote }}</p>
            <p><span class="font-semibold text-gray-200">Cantidad:</span> {{ $lote->cantidad }}</p>

            <p>
                <span class="font-semibold text-gray-200">Estado:</span>

                <span class="px-2 py-1 rounded text-xs font-semibold ml-1
                    @if($lote->estado == 'pendiente') bg-yellow-500/20 text-yellow-300
                    @elseif($lote->estado == 'enviado') bg-green-500/20 text-green-300
                    @else bg-red-500/20 text-red-300 @endif">
                    {{ ucfirst($lote->estado) }}
                </span>
            </p>

            <p><span class="font-semibold text-gray-200">Protocolo:</span> {{ $lote->protocolo ?? '-' }}</p>
        </div>

        <hr class="my-6 border-gray-700">

        <!-- Documentos incluidos -->
        <h3 class="text-xl font-bold text-gray-100 mb-3">Documentos incluidos</h3>

        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-800 text-gray-200">
                    <th class="border border-gray-700 px-3 py-2">CDC</th>
                    <th class="border border-gray-700 px-3 py-2">Tipo</th>
                    <th class="border border-gray-700 px-3 py-2">Total</th>
                    <th class="border border-gray-700 px-3 py-2">Acciones</th>
                </tr>
            </thead>

            <tbody class="text-gray-300">
                @foreach($lote->documentos as $doc)
                <tr class="hover:bg-gray-800 transition">
                    <td class="border border-gray-700 px-3 py-2">{{ $doc->cdc }}</td>
                    <td class="border border-gray-700 px-3 py-2">{{ $doc->tipo_documento }}</td>
                    <td class="border border-gray-700 px-3 py-2">
                        {{ number_format($doc->total, 0, ',', '.') }} Gs.
                    </td>
                    <td class="border border-gray-700 px-3 py-2">
                        <a href="{{ route('documentos.show', $doc->id) }}"
                           class="text-indigo-400 hover:text-indigo-300 font-medium">
                            Ver documento
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botón volver -->
        <div class="mt-6">
            <a href="{{ route('lotes.index') }}"
               class="inline-block px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 rounded transition">
                ← Volver a Lotes
            </a>
        </div>

    </div>
</x-app-layout>
