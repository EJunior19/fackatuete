<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-100">Editar Cliente</h1>
    </x-slot>

    <div class="bg-gray-900 border border-gray-700 p-6 rounded-lg shadow max-w-xl mx-auto">

        <form method="POST" action="{{ route('clientes.update', $cliente->id) }}" class="text-gray-300">
            @csrf
            @method('PUT')

            {{-- RUC --}}
            <div class="mb-4">
                <label class="text-gray-400">RUC</label>
                <input name="ruc" value="{{ $cliente->ruc }}"
                       class="bg-gray-800 border border-gray-700 w-full rounded px-3 py-2 text-gray-200
                       focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- DV --}}
            <div class="mb-4">
                <label class="text-gray-400">DV</label>
                <input name="dv" value="{{ $cliente->dv }}"
                       class="bg-gray-800 border border-gray-700 w-full rounded px-3 py-2 text-gray-200
                       focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- Razón Social --}}
            <div class="mb-4">
                <label class="text-gray-400">Razón Social</label>
                <input name="razon_social" value="{{ $cliente->razon_social }}"
                       class="bg-gray-800 border border-gray-700 w-full rounded px-3 py-2 text-gray-200
                       focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- Teléfono --}}
            <div class="mb-4">
                <label class="text-gray-400">Teléfono</label>
                <input name="telefono" value="{{ $cliente->telefono }}"
                       class="bg-gray-800 border border-gray-700 w-full rounded px-3 py-2 text-gray-200
                       focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="text-gray-400">Email</label>
                <input type="email" name="email" value="{{ $cliente->email }}"
                       class="bg-gray-800 border border-gray-700 w-full rounded px-3 py-2 text-gray-200
                       focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- Dirección --}}
            <div class="mb-4">
                <label class="text-gray-400">Dirección</label>
                <input name="direccion" value="{{ $cliente->direccion }}"
                       class="bg-gray-800 border border-gray-700 w-full rounded px-3 py-2 text-gray-200
                       focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- Botón --}}
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition">
                Actualizar
            </button>

        </form>

    </div>
</x-app-layout>
