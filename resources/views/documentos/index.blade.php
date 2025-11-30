<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-100">📄 Documentos Electrónicos</h1>
    </x-slot>

    <div class="bg-gray-900 border border-gray-700 p-6 rounded-lg shadow">

        <!-- Filtros -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <form method="GET" class="flex flex-wrap items-center gap-3">

                <input type="text" name="buscar"
                    class="bg-gray-800 text-gray-200 border border-gray-600 rounded px-3 py-2 focus:ring focus:ring-blue-500 w-48"
                    placeholder="Buscar CDC o RUC..."
                    value="{{ request('buscar') }}">

                <select name="tipo"
                    class="bg-gray-800 text-gray-200 border border-gray-600 rounded px-3 py-2 focus:ring focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="FE" {{ request('tipo') == 'FE' ? 'selected' : '' }}>Factura Electrónica</option>
                    <option value="ND" {{ request('tipo') == 'ND' ? 'selected' : '' }}>Nota de Débito</option>
                    <option value="NC" {{ request('tipo') == 'NC' ? 'selected' : '' }}>Nota de Crédito</option>
                </select>

                <select name="estado"
                    class="bg-gray-800 text-gray-200 border border-gray-600 rounded px-3 py-2 focus:ring focus:ring-blue-500">
                    <option value="">Estado</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                    Filtrar
                </button>
            </form>

            <a href="{{ route('documentos.create') }}"
                class="bg-green-600 hover:bg-green-700 px-4 py-2 text-white rounded shadow">
                + Nuevo Documento
            </a>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto rounded-lg border border-gray-700">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-800 text-gray-300 uppercase text-xs">
                        <th class="px-3 py-3 text-left border-b border-gray-700">CDC</th>
                        <th class="px-3 py-3 text-left border-b border-gray-700">Tipo</th>
                        <th class="px-3 py-3 text-left border-b border-gray-700">Cliente</th>
                        <th class="px-3 py-3 text-left border-b border-gray-700">Fecha</th>
                        <th class="px-3 py-3 text-right border-b border-gray-700">Total</th>
                        <th class="px-3 py-3 text-left border-b border-gray-700">Estado</th>
                        <th class="px-3 py-3 text-center border-b border-gray-700">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-700">

                    @foreach($documentos as $doc)
                        <tr class="hover:bg-gray-800 transition">
                            <td class="px-3 py-2 text-gray-200">{{ $doc->cdc }}</td>
                            <td class="px-3 py-2 text-gray-200">{{ $doc->tipo_documento }}</td>
                            <td class="px-3 py-2 text-gray-200">{{ $doc->cliente_nombre }}</td>
                            <td class="px-3 py-2 text-gray-200">{{ $doc->fecha_emision }}</td>

                            <td class="px-3 py-2 text-right text-gray-200">
                                {{ number_format($doc->total, 0, ',', '.') }}
                            </td>

                            <td class="px-3 py-2">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    @if($doc->estado == 'pendiente')
                                        bg-yellow-700 text-yellow-200
                                    @elseif($doc->estado == 'enviado')
                                        bg-green-700 text-green-200
                                    @else
                                        bg-red-700 text-red-200
                                    @endif">
                                    {{ ucfirst($doc->estado) }}
                                </span>
                            </td>

                            <td class="px-3 py-2 text-center space-x-3">

                                <a href="{{ route('documentos.show', $doc->id) }}"
                                   class="text-blue-400 hover:text-blue-300">
                                   Ver
                                </a>

                                <a href="{{ route('documentos.edit', $doc->id) }}"
                                   class="text-yellow-400 hover:text-yellow-300">
                                   Editar
                                </a>

                                <form action="{{ route('documentos.enviar', $doc->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-green-400 hover:text-green-300">
                                        Enviar
                                    </button>
                                </form>

                                <a href="{{ route('documentos.pdf', $doc->id) }}"
                                   class="text-red-400 hover:text-red-300"
                                   target="_blank">
                                   PDF
                                </a>

                            </td>
                        </tr>
                    @endforeach

                    @if($documentos->count() == 0)
                        <tr>
                            <td colspan="7"
                                class="text-center py-6 text-gray-400">
                                No se encontraron documentos
                            </td>
                        </tr>
                    @endif

                </tbody>

            </table>
        </div>

        <div class="mt-4">
            {{ $documentos->links() }}
        </div>

    </div>

</x-app-layout>
