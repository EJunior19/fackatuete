<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-100">Nuevo Producto</h1>
    </x-slot>

    <div class="bg-gray-900 border border-gray-700 p-6 rounded-lg shadow max-w-3xl mx-auto">

        <form action="{{ route('productos.store') }}" method="POST">
            @csrf

            @include('productos.partials._form')

            <div class="flex justify-end">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded mt-4 transition">
                    Guardar
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
