<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-100">
            Evento #{{ $evento->id }}
        </h2>
    </x-slot>

    <div class="bg-gray-900 p-6 rounded-xl shadow-lg max-w-4xl mx-auto border border-gray-700">

        <h3 class="font-bold text-xl text-gray-100 mb-4">
            Información del Evento
        </h3>

        <div class="space-y-1 text-gray-300">
            <p><strong class="text-gray-200">Código:</strong> {{ $evento->codigo }}</p>
            <p><strong class="text-gray-200">Tipo:</strong> {{ $evento->tipo }}</p>
            <p><strong class="text-gray-200">Descripción:</strong> {{ $evento->descripcion }}</p>
            <p><strong class="text-gray-200">Mensaje:</strong> {{ $evento->mensaje }}</p>
            <p><strong class="text-gray-200">Fecha:</strong> {{ $evento->created_at }}</p>
        </div>

        @if($evento->documento)
            <p class="mt-4 text-gray-300">
                <strong class="text-gray-200">Documento relacionado:</strong>
                <a class="text-indigo-400 hover:text-indigo-300 font-semibold"
                   href="{{ route('documentos.show', $evento->documento->id) }}">
                    {{ $evento->documento->cdc }}
                </a>
            </p>
        @endif

        @if($evento->lote)
            <p class="mt-2 text-gray-300">
                <strong class="text-gray-200">Lote relacionado:</strong>
                <a class="text-indigo-400 hover:text-indigo-300 font-semibold"
                   href="{{ route('lotes.show', $evento->lote->id) }}">
                    #{{ $evento->lote->numero_lote }}
                </a>
            </p>
        @endif

        <hr class="my-6 border-gray-700">

        <h3 class="font-bold text-lg text-gray-100 mb-2">XML</h3>

        <pre class="bg-gray-800 border border-gray-700 text-gray-200 p-4 rounded-lg text-xs overflow-x-auto whitespace-pre-wrap">
{{ $evento->xml }}
        </pre>

        {{-- Botón Volver --}}
        <div class="mt-6">
            <a href="{{ route('eventos.index') }}"
               class="px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                ← Volver a Eventos
            </a>
        </div>

    </div>
</x-app-layout>
