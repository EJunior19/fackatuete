<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-100">Editar Producto</h1>
    </x-slot>

    <div class="bg-gray-900 border border-gray-700 p-6 rounded-lg shadow max-w-4xl mx-auto">

        <form method="POST" action="{{ route('productos.update', $producto->id) }}">
            @csrf
            @method('PUT')

            {{-- Formulario (reutilizado del partial) --}}
            @include('productos.partials._form', [
                'producto' => $producto
            ])

            {{-- BOTÓN PARA ACTUALIZAR --}}
            <div class="mt-6 flex justify-end">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition">
                    Actualizar Producto
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
