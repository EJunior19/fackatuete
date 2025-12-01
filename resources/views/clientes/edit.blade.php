@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')

<div class="max-w-xl mx-auto space-y-4">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            ✏️ Editar Cliente
        </h1>

        <a href="{{ route('clientes.index') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            ← Volver a clientes
        </a>
    </div>

    {{-- Card principal --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Modificar datos del cliente
        </h2>

        <form method="POST" action="{{ route('clientes.update', $cliente->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- RUC --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">RUC</label>
                <input name="ruc" value="{{ $cliente->ruc }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- DV --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">DV</label>
                <input name="dv" value="{{ $cliente->dv }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- Razón Social --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Razón Social</label>
                <input name="razon_social" value="{{ $cliente->razon_social }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- Teléfono --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input name="telefono" value="{{ $cliente->telefono }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ $cliente->email }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- Dirección --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input name="direccion" value="{{ $cliente->direccion }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            {{-- Botón --}}
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-sm transition">
                Actualizar
            </button>

        </form>

    </div>

</div>

@endsection
