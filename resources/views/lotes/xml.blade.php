<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-100">
            XML del lote
        </h-slot>
    </x-slot>

    <div class="bg-gray-900 p-6 rounded-xl shadow-lg border border-gray-700 max-w-4xl mx-auto">

        <!-- XML Enviado -->
        <h3 class="text-lg font-bold text-gray-200 mb-2">XML Enviado</h3>

        <pre class="bg-gray-800 text-gray-300 p-4 rounded-lg text-xs overflow-x-auto border border-gray-700 whitespace-pre-wrap">
{{ $xml }}
        </pre>

        <!-- XML Respuesta -->
        @if($xml_recibido)
            <h3 class="text-lg font-bold text-gray-200 mt-6 mb-2">XML Respuesta SET</h3>

            <pre class="bg-gray-800 text-gray-300 p-4 rounded-lg text-xs overflow-x-auto border border-gray-700 whitespace-pre-wrap">
{{ $xml_recibido }}
            </pre>
        @endif

        <!-- Volver -->
        <div class="mt-6">
            <a href="{{ route('lotes.index') }}"
               class="inline-block px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 rounded transition">
                ← Volver a Lotes
            </a>
        </div>

    </div>
</x-app-layout>
