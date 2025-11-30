<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-100">Detalle del Producto</h1>
    </x-slot>

    <div class="bg-gray-900 border border-gray-700 p-6 rounded-lg shadow max-w-3xl mx-auto">

        {{-- Título --}}
        <h1 class="text-2xl font-bold mb-4 text-gray-100">
            {{ $producto->descripcion }}
        </h1>

        {{-- INFORMACIÓN GENERAL --}}
        <div class="space-y-2 text-sm text-gray-300">

            <p><strong class="text-gray-400">Código:</strong> {{ $producto->codigo }}</p>
            <p><strong class="text-gray-400">Categoría:</strong> {{ $producto->categoria ?? '-' }}</p>
            <p><strong class="text-gray-400">Unidad:</strong> {{ $producto->unidad_medida }}</p>
            <p><strong class="text-gray-400">IVA:</strong> {{ $producto->iva }}%</p>

            <p>
                <strong class="text-gray-400">Estado:</strong>
                @if($producto->activo)
                    <span class="px-2 py-1 text-xs rounded bg-green-900 text-green-300 border border-green-700">
                        Activo
                    </span>
                @else
                    <span class="px-2 py-1 text-xs rounded bg-red-900 text-red-300 border border-red-700">
                        Inactivo
                    </span>
                @endif
            </p>

        </div>

        <hr class="my-5 border-gray-700">

        {{-- PRECIOS --}}
        <h2 class="font-semibold text-lg text-gray-100 mb-3">Precios</h2>

        <div class="grid grid-cols-1 gap-2 text-sm text-gray-300">

            <p>
                <strong class="text-gray-400">Precio 1:</strong>
                Gs. {{ number_format($producto->precio_1, 0, '.', '.') }}
            </p>

            @if($producto->precio_2)
            <p>
                <strong class="text-gray-400">Precio 2:</strong>
                Gs. {{ number_format($producto->precio_2, 0, '.', '.') }}
            </p>
            @endif

            @if($producto->precio_3)
            <p>
                <strong class="text-gray-400">Precio 3:</strong>
                Gs. {{ number_format($producto->precio_3, 0, '.', '.') }}
            </p>
            @endif
        </div>

        {{-- BOTONES --}}
        <div class="mt-8 flex justify-between">

            {{-- VOLVER --}}
            <a href="{{ route('productos.index') }}"
                class="px-4 py-2 bg-gray-800 text-gray-300 rounded border border-gray-600 hover:bg-gray-700 transition">
                ← Volver
            </a>

            <div class="flex gap-3">

                {{-- EDITAR --}}
                <a href="{{ route('productos.edit', $producto) }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                    Editar
                </a>

                {{-- ACTIVAR / DESACTIVAR --}}
                @if($producto->activo)
                    <form method="POST" action="{{ route('productos.desactivar', $producto->id) }}">
                        @csrf
                        <button
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">
                            Desactivar
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('productos.activar', $producto->id) }}">
                        @csrf
                        <button
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded transition">
                            Activar
                        </button>
                    </form>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>
