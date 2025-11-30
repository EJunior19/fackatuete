<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-100">Productos</h1>
    </x-slot>

    <div class="bg-gray-900 border border-gray-700 p-6 rounded-lg shadow">

        {{-- Filtros + botón --}}
        <div class="flex justify-between items-center mb-5">

            {{-- BUSCADOR --}}
            <form method="GET" class="flex space-x-2">
                <input type="text" name="buscar"
                       class="bg-gray-800 border border-gray-700 text-gray-200 rounded px-3 py-2 w-64 focus:ring focus:ring-blue-600 focus:outline-none"
                       placeholder="Buscar..." value="{{ request('buscar') }}">

                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                    Buscar
                </button>
            </form>

            {{-- NUEVO PRODUCTO --}}
            <a href="{{ route('productos.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition shadow">
                + Nuevo Producto
            </a>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-gray-300">
                        <th class="border border-gray-700 px-3 py-2">Código</th>
                        <th class="border border-gray-700 px-3 py-2">Descripción</th>
                        <th class="border border-gray-700 px-3 py-2">IVA</th>
                        <th class="border border-gray-700 px-3 py-2">Precio</th>
                        <th class="border border-gray-700 px-3 py-2">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($productos as $p)
                    <tr class="hover:bg-gray-800 transition">
                        <td class="border border-gray-800 px-3 py-2 text-gray-200">{{ $p->codigo }}</td>
                        <td class="border border-gray-800 px-3 py-2 text-gray-200">{{ $p->descripcion }}</td>
                        <td class="border border-gray-800 px-3 py-2 text-gray-200">{{ $p->iva }}%</td>
                        <td class="border border-gray-800 px-3 py-2 text-gray-200">
                            Gs. {{ number_format($p->precio_1,0,'.','.') }}
                        </td>
                        <td class="border border-gray-800 px-3 py-2 space-x-4">

                            <a class="text-blue-400 hover:text-blue-300"
                               href="{{ route('productos.show', $p) }}">
                                Ver
                            </a>

                            <a class="text-orange-400 hover:text-orange-300"
                               href="{{ route('productos.edit', $p) }}">
                                Editar
                            </a>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4 text-gray-300">
            {{ $productos->links() }}
        </div>

    </div>
</x-app-layout>
