@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<div class="max-w-6xl mx-auto space-y-4">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            👥 Clientes
        </h1>

        <a href="{{ route('clientes.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-sm">
            + Nuevo Cliente
        </a>
    </div>

    {{-- Contenedor principal --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6">

        {{-- Buscador --}}
        <form method="GET" class="flex flex-wrap gap-3 mb-4">

            <input 
                type="text" 
                name="buscar" 
                placeholder="Buscar RUC o Nombre..."
                value="{{ request('buscar') }}"
                class="border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 w-60"
            >

            <button 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-sm">
                Buscar
            </button>

        </form>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">

                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <th class="border border-gray-200 px-3 py-2 text-left">RUC</th>
                        <th class="border border-gray-200 px-3 py-2 text-left">Razón Social</th>
                        <th class="border border-gray-200 px-3 py-2 text-left">Teléfono</th>
                        <th class="border border-gray-200 px-3 py-2 text-left">Email</th>
                        <th class="border border-gray-200 px-3 py-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    @foreach($clientes as $cli)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="border border-gray-200 px-3 py-2">
                                {{ $cli->ruc }}-{{ $cli->dv }}
                            </td>

                            <td class="border border-gray-200 px-3 py-2">
                                {{ $cli->razon_social }}
                            </td>

                            <td class="border border-gray-200 px-3 py-2">
                                {{ $cli->telefono }}
                            </td>

                            <td class="border border-gray-200 px-3 py-2">
                                {{ $cli->email }}
                            </td>

                            <td class="border border-gray-200 px-3 py-2 text-center">

                                <div class="flex justify-center items-center gap-4">

                                    {{-- EDITAR --}}
                                    <a href="{{ route('clientes.edit', $cli->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        Editar
                                    </a>

                                    {{-- ELIMINAR --}}
                                    <x-confirm-delete 
                                        :action="route('clientes.destroy', $cli->id)" 
                                        message="¿Seguro que desea eliminar este cliente?"
                                    />

                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $clientes->links() }}
        </div>

    </div>

</div>

@endsection
