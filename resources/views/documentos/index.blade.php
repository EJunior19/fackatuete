@extends('layouts.app')

@section('title', 'Documentos Electrónicos')

@section('content')

<div class="space-y-6">

    {{-- Título --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            📄 Documentos Electrónicos
        </h1>

        <a href="{{ route('documentos.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
            + Nuevo documento
        </a>
    </div>

    {{-- Contenedor principal --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-4 space-y-4">

        {{-- Filtros --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            <form method="GET" class="flex flex-wrap items-center gap-3 text-sm">

                <input
                    type="text"
                    name="buscar"
                    class="w-52 rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Buscar CDC o RUC..."
                    value="{{ request('buscar') }}">

                <select
                    name="tipo"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los tipos</option>
                    <option value="FE" {{ request('tipo') == 'FE' ? 'selected' : '' }}>Factura electrónica</option>
                    <option value="ND" {{ request('tipo') == 'ND' ? 'selected' : '' }}>Nota de débito</option>
                    <option value="NC" {{ request('tipo') == 'NC' ? 'selected' : '' }}>Nota de crédito</option>
                </select>

                <select
                    name="estado"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>

                <button
                    class="inline-flex items-center px-4 py-2 rounded-md bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 border border-gray-300">
                    Filtrar
                </button>
            </form>

        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto border border-gray-200 rounded-md">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-600">
                        <th class="px-3 py-2 text-left border-b border-gray-200">CDC</th>
                        <th class="px-3 py-2 text-left border-b border-gray-200">Tipo</th>
                        <th class="px-3 py-2 text-left border-b border-gray-200">Cliente</th>
                        <th class="px-3 py-2 text-left border-b border-gray-200">Fecha</th>
                        <th class="px-3 py-2 text-right border-b border-gray-200">Total</th>
                        <th class="px-3 py-2 text-left border-b border-gray-200">Estado</th>
                        <th class="px-3 py-2 text-center border-b border-gray-200">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($documentos as $doc)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-gray-800">{{ $doc->cdc }}</td>
                            <td class="px-3 py-2 text-gray-800">{{ $doc->tipo_documento }}</td>
                            <td class="px-3 py-2 text-gray-800">{{ $doc->cliente_nombre }}</td>
                            <td class="px-3 py-2 text-gray-800">{{ $doc->fecha_emision }}</td>

                            <td class="px-3 py-2 text-right text-gray-800">
                                {{ number_format($doc->total, 0, ',', '.') }}
                            </td>

                            <td class="px-3 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($doc->estado == 'pendiente')
                                        bg-yellow-100 text-yellow-700
                                    @elseif($doc->estado == 'enviado')
                                        bg-blue-100 text-blue-700
                                    @elseif($doc->estado == 'rechazado')
                                        bg-red-100 text-red-700
                                    @else
                                        bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($doc->estado) }}
                                </span>
                            </td>

                            <td class="px-3 py-2 text-center space-x-2">

                                <a href="{{ route('documentos.show', $doc->id) }}"
                                class="text-xs text-blue-600 hover:text-blue-800">
                                    Ver
                                </a>

                                <a href="{{ route('documentos.edit', $doc->id) }}"
                                class="text-xs text-amber-600 hover:text-amber-800">
                                    Editar
                                </a>

                                <form action="{{ route('documentos.enviar', $doc->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs text-green-600 hover:text-green-800">
                                        Enviar
                                    </button>
                                </form>

                                <a href="{{ route('documentos.pdf', $doc->id) }}"
                                class="text-xs text-red-600 hover:text-red-800"
                                target="_blank">
                                PDF
                                </a>

                                <!-- 🚀 NUEVO: Botón para firmar XML -->
                                <a href="{{ route('documentos.firmar', $doc->id) }}"
                                class="text-xs text-emerald-600 hover:text-emerald-800">
                                    🔐 Firmar XML
                                </a>

</td>
                        </tr>
                    @endforeach

                    @if($documentos->count() == 0)
                        <tr>
                            <td colspan="7" class="text-center py-6 text-sm text-gray-500">
                                No se encontraron documentos.
                            </td>
                        </tr>
                    @endif

                </tbody>

            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $documentos->links() }}
        </div>

    </div>

</div>

@endsection
