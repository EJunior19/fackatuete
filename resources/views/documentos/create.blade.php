@extends('layouts.app')

@section('title', 'Nuevo documento electrónico')

@section('content')

<div class="max-w-5xl mx-auto space-y-4">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            🧾 Nuevo documento electrónico
        </h1>

        <a href="{{ route('documentos.index') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            ← Volver a documentos
        </a>
    </div>

    {{-- Card principal --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6">

        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
                Crear documento
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Complete los datos del cliente, productos e impuestos antes de guardar.
            </p>
        </div>

        {{-- Formulario --}}
        <div class="mt-4">
            @include('documentos._form', [
                'action' => route('documentos.store'),
                'method' => 'POST',
                'buttonText' => 'Guardar documento'
            ])
        </div>

    </div>

</div>

@endsection
