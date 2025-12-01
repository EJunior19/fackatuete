@extends('layouts.app')

@section('title', 'Detalle del producto')

@section('content')

<div class="max-w-3xl mx-auto space-y-4">

    {{-- ENCABEZADO --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            Detalle del producto
        </h1>

        <a href="{{ route('productos.index') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            ← Volver a productos
        </a>
    </div>

    {{-- CARD PRINCIPAL --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6">

        {{-- Título --}}
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            {{ $producto->descripcion }}
        </h2>

        {{-- INFORMACIÓN GENERAL --}}
        <div class="space-y-2 text-sm text-gray-700">

            <p>
                <span class="font-semibold text-gray-600">Código:</span>
                {{ $producto->codigo }}
            </p>

            <p>
                <span class="font-semibold text-gray-600">Categoría:</span>
                {{ $producto->categoria ?? '-' }}
            </p>

            <p>
                <span class="font-semibold text-gray-600">Unidad:</span>
                {{ $producto->unidad_medida }}
            </p>

            <p>
                <span class="font-semibold text-gray-600">IVA:</span>
                {{ $producto->iva }}%
            </p>

            <p>
                <span class="font-semibold text-gray-600">Estado:</span>
                @if($producto->activo)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-300">
                        Activo
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-300">
                        Inactivo
                    </span>
                @endif
            </p>

        </div>

        <hr class="my-5 border-gray-200">

        {{-- PRECIOS --}}
        <h3 class="font-semibold text-lg text-gray-800 mb-3">Precios</h3>

        <div class="grid grid-cols-1 gap-2 text-sm text-gray-700">

            <p>
                <span class="font-semibold text-gray-600">Precio 1:</span>
                Gs. {{ number_format($producto->precio_1, 0, '.', '.') }}
            </p>

            @if($producto->precio_2)
                <p>
                    <span class="font-semibold text-gray-600">Precio 2:</span>
                    Gs. {{ number_format($producto->precio_2, 0, '.', '.') }}
                </p>
            @endif

            @if($producto->precio_3)
                <p>
                    <span class="font-semibold text-gray-600">Precio 3:</span>
                    Gs. {{ number_format($producto->precio_3, 0, '.', '.') }}
                </p>
            @endif

        </div>

        {{-- BOTONES --}}
        <div class="mt-8 flex justify-between items-center">

            <a href="{{ route('productos.index') }}"
               class="px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-md hover:bg-gray-50 transition">
                ← Volver
            </a>

            <div class="flex gap-3">

                {{-- EDITAR --}}
                <a href="{{ route('productos.edit', $producto) }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm transition">
                    Editar
                </a>

                {{-- ACTIVAR / DESACTIVAR --}}
                @if($producto->activo)
                    <form method="POST" action="{{ route('productos.desactivar', $producto->id) }}">
                        @csrf
                        <button
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md shadow-sm transition">
                            Desactivar
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('productos.activar', $producto->id) }}">
                        @csrf
                        <button
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition">
                            Activar
                        </button>
                    </form>
                @endif

            </div>
        </div>

    </div>

</div>

@endsection
