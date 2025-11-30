<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-100">Nuevo Cliente</h1>
    </x-slot>

    <div class="bg-gray-900 border border-gray-700 p-6 rounded-lg shadow max-w-xl mx-auto">

        <form method="POST" action="{{ route('clientes.store') }}" class="text-gray-300">
            @csrf

            {{-- RUC --}}
            <div class="mb-4">
                <label class="text-gray-400">RUC</label>
                <input name="ruc" maxlength="15"
                    class="bg-gray-800 border border-gray-700 text-gray-200 w-full rounded px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- DV --}}
            <div class="mb-4">
                <label class="text-gray-400">DV</label>
                <input name="dv" maxlength="2"
                    class="bg-gray-800 border border-gray-700 text-gray-200 w-full rounded px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- RAZÓN SOCIAL --}}
            <div class="mb-4">
                <label class="text-gray-400">Razón Social</label>
                <input name="razon_social" maxlength="150"
                    class="bg-gray-800 border border-gray-700 text-gray-200 w-full rounded px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- TELÉFONO --}}
            <div class="mb-4">
                <label class="text-gray-400">Teléfono</label>
                <input name="telefono" maxlength="20"
                    class="bg-gray-800 border border-gray-700 text-gray-200 w-full rounded px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- EMAIL --}}
            <div class="mb-4">
                <label class="text-gray-400">Email</label>
                <input type="email" name="email" maxlength="120"
                    class="bg-gray-800 border border-gray-700 text-gray-200 w-full rounded px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- DIRECCIÓN --}}
            <div class="mb-4">
                <label class="text-gray-400">Dirección</label>
                <input name="direccion" maxlength="255"
                    class="bg-gray-800 border border-gray-700 text-gray-200 w-full rounded px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- BOTÓN --}}
            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition">
                Guardar
            </button>

        </form>

    </div>
</x-app-layout>
