@extends('layouts.app')

@section('title', 'Evento #' . $evento->id)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            Evento #{{ $evento->id }}
        </h2>
    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">

        {{-- TÍTULO --}}
        <h3 class="font-bold text-xl text-gray-800 mb-4">
            Información del Evento
        </h3>

        {{-- DATOS --}}
        <div class="space-y-2 text-sm text-gray-700">

            <p><strong class="text-gray-800">Código:</strong> {{ $evento->codigo }}</p>
            <p><strong class="text-gray-800">Tipo:</strong> {{ $evento->tipo }}</p>
            <p><strong class="text-gray-800">Descripción:</strong> {{ $evento->descripcion }}</p>
            <p><strong class="text-gray-800">Mensaje:</strong> {{ $evento->mensaje }}</p>
            <p><strong class="text-gray-800">Fecha:</strong> {{ $evento->created_at }}</p>

        </div>

        {{-- DOCUMENTO --}}
        @if($evento->documento)
            <p class="mt-4 text-sm text-gray-700">
                <strong class="text-gray-800">Documento relacionado:</strong>
                <a class="text-blue-600 hover:text-blue-800 font-medium"
                   href="{{ route('documentos.show', $evento->documento->id) }}">
                    {{ $evento->documento->cdc }}
                </a>
            </p>
        @endif

        {{-- LOTE --}}
        @if($evento->lote)
            <p class="mt-2 text-sm text-gray-700">
                <strong class="text-gray-800">Lote relacionado:</strong>
                <a class="text-blue-600 hover:text-blue-800 font-medium"
                   href="{{ route('lotes.show', $evento->lote->id) }}">
                    #{{ $evento->lote->numero_lote }}
                </a>
            </p>
        @endif

        <hr class="my-6 border-gray-300">

        {{-- XML --}}
        <h3 class="font-bold text-lg text-gray-800 mb-2">XML</h3>

        <pre class="bg-gray-100 border border-gray-300 text-gray-800 p-4 rounded-lg text-xs overflow-x-auto whitespace-pre-wrap">
{{ $evento->xml }}
        </pre>

        {{-- VOLVER --}}
        <div class="mt-6">
            <a href="{{ route('eventos.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded hover:bg-gray-200 transition">
                ← Volver a Eventos
            </a>
        </div>

    </div>

</div>

@endsection
