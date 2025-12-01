@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')

<div class="max-w-4xl mx-auto space-y-4">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            ✏️ Editar producto
        </h1>

        <a href="{{ route('productos.index') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            ← Volver a productos
        </a>
    </div>

    {{-- Card principal --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Modificar datos del producto
        </h2>

        <form method="POST" action="{{ route('productos.update', $producto->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Partial ya estandarizado --}}
            @include('productos.partials._form', [
                'producto' => $producto
            ])

            {{-- Botón actualizar --}}
            <div class="mt-6 flex justify-end">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-sm transition">
                    Actualizar producto
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
