@extends('layouts.app')

@section('title', 'Nuevo producto')

@section('content')

<div class="max-w-3xl mx-auto space-y-4">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            📦 Nuevo producto
        </h1>

        <a href="{{ route('productos.index') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            ← Volver a productos
        </a>
    </div>

    {{-- Card principal --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Registrar producto
        </h2>

        <form action="{{ route('productos.store') }}" method="POST" class="space-y-4">
            @csrf

            @include('productos.partials._form')

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded mt-4 transition">
                Guardar
            </button>

            </div>

        </form>

    </div>

</div>

@endsection
