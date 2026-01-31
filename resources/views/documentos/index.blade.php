@extends('layouts.app')

@section('title', 'Documentos Electrónicos')

@section('content')

<div class="space-y-6">

    {{-- TÍTULO --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            📄 Documentos Electrónicos
        </h1>

        <a href="{{ route('documentos.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
            + Nuevo documento
        </a>
    </div>

    {{-- CONTENEDOR --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-4 space-y-4">

        {{-- FILTROS --}}
        <form method="GET" class="flex flex-wrap items-center gap-3 text-sm">

            <input
                type="text"
                name="buscar"
                value="{{ request('buscar') }}"
                placeholder="Buscar CDC o RUC..."
                class="w-52 rounded-md border border-gray-300 px-3 py-2 text-sm
                       focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

            <select name="tipo"
                class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                <option value="">Todos los tipos</option>
                <option value="FE" {{ request('tipo') === 'FE' ? 'selected' : '' }}>Factura electrónica</option>
                <option value="ND" {{ request('tipo') === 'ND' ? 'selected' : '' }}>Nota de débito</option>
                <option value="NC" {{ request('tipo') === 'NC' ? 'selected' : '' }}>Nota de crédito</option>
            </select>

            <select name="estado"
                class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="firmado" {{ request('estado') === 'firmado' ? 'selected' : '' }}>Firmado</option>
                <option value="enviado" {{ request('estado') === 'enviado' ? 'selected' : '' }}>Enviado</option>
                <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
            </select>

            <button
                class="px-4 py-2 rounded-md bg-gray-100 border text-sm hover:bg-gray-200">
                Filtrar
            </button>
        </form>

        {{-- TABLA --}}
        <div class="overflow-x-auto border border-gray-200 rounded-md">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-3 py-2 text-left border-b">CDC</th>
                        <th class="px-3 py-2 text-left border-b">Tipo</th>
                        <th class="px-3 py-2 text-left border-b">Cliente</th>
                        <th class="px-3 py-2 text-left border-b">Fecha</th>
                        <th class="px-3 py-2 text-right border-b">Total</th>
                        <th class="px-3 py-2 text-left border-b">Estado</th>
                        <th class="px-3 py-2 text-center border-b">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($documentos as $doc)
                        <tr class="hover:bg-gray-50">

                            <td class="px-3 py-2">{{ $doc->cdc ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $doc->tipo_documento }}</td>
                            <td class="px-3 py-2">{{ $doc->cliente_nombre }}</td>
                            <td class="px-3 py-2">{{ $doc->fecha_emision }}</td>

                            <td class="px-3 py-2 text-right">
                                {{ number_format($doc->total_general, 0, ',', '.') }}
                            </td>

                            {{-- ESTADO --}}
                            <td class="px-3 py-2">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium
                                    @class([
                                        'bg-yellow-100 text-yellow-700' => $doc->estado_sifen === 'pendiente',
                                        'bg-blue-100 text-blue-700'     => $doc->estado_sifen === 'firmado',
                                        'bg-green-100 text-green-700'   => $doc->estado_sifen === 'enviado',
                                        'bg-red-100 text-red-700'       => $doc->estado_sifen === 'rechazado',
                                    ])">
                                    {{ ucfirst($doc->estado_sifen) }}
                                </span>
                            </td>

                            {{-- ACCIONES --}}
                            <td class="px-3 py-2 text-center space-x-2">

                                <a href="{{ route('documentos.show', $doc->id) }}"
                                   class="text-xs text-blue-600 hover:underline">
                                    Ver
                                </a>

                                <a href="{{ route('documentos.edit', $doc->id) }}"
                                   class="text-xs text-amber-600 hover:underline">
                                    Editar
                                </a>

                                {{-- PDF (DESCARGA SEGURA) --}}
                                <a href="{{ route('documentos.pdf', $doc->id) }}"
                                   class="text-xs text-red-600 hover:underline">
                                    PDF
                                </a>

                                {{-- FIRMAR --}}
                                @if($doc->estado_sifen === 'pendiente')
                                    <form action="{{ route('documentos.firmar', $doc->id) }}"
                                          method="GET"
                                          class="inline">
                                        <button class="text-xs text-emerald-600 hover:underline">
                                            🔐 Firmar
                                        </button>
                                    </form>
                                @endif

                                {{-- ENVIAR --}}
                                @if($doc->estado_sifen === 'firmado')
                                    <form action="{{ route('documentos.enviar', $doc->id) }}"
                                          method="POST"
                                          class="inline">
                                        @csrf
                                        <button class="text-xs text-green-600 hover:underline">
                                            📤 Enviar
                                        </button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">
                                No se encontraron documentos.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-4">
            {{ $documentos->withQueryString()->links() }}
        </div>

    </div>
</div>

@endsection
