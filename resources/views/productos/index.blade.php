@extends('layouts.app')

@section('title', 'Productos')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- ENCABEZADO --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            📦 Productos
        </h1>

        <a href="{{ route('productos.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow-sm transition">
            + Nuevo Producto
        </a>
    </div>

    {{-- CARD PRINCIPAL --}}
    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6">

        {{-- FILTRO --}}
        <form method="GET" class="flex items-center space-x-2 mb-4">

            <input
                type="text"
                name="buscar"
                placeholder="Buscar por descripción o código..."
                value="{{ request('buscar') }}"
                class="w-64 bg-gray-50 border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm transition">
                Buscar
            </button>

        </form>

        {{-- TABLA --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse text-gray-700">

                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border border-gray-200 px-3 py-2 text-left">Código</th>
                        <th class="border border-gray-200 px-3 py-2 text-left">Descripción</th>
                        <th class="border border-gray-200 px-3 py-2 text-center">IVA</th>
                        <th class="border border-gray-200 px-3 py-2 text-right">Precio 1</th>
                        <th class="border border-gray-200 px-3 py-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($productos as $p)
                    <tr class="hover:bg-gray-50">

                        <td class="border border-gray-200 px-3 py-2">{{ $p->codigo }}</td>

                        <td class="border border-gray-200 px-3 py-2">{{ $p->descripcion }}</td>

                        <td class="border border-gray-200 px-3 py-2 text-center">{{ $p->iva }}%</td>

                        <td class="border border-gray-200 px-3 py-2 text-right">
                            Gs. {{ number_format($p->precio_1,0,'.','.') }}
                        </td>

                        <td class="border border-gray-200 px-3 py-2 text-center space-x-4">

                            {{-- VER --}}
                            <a href="{{ route('productos.show', $p) }}"
                               class="text-blue-600 hover:text-blue-800 font-semibold">
                                Ver
                            </a>

                            {{-- EDITAR --}}
                            <a href="{{ route('productos.edit', $p) }}"
                               class="text-orange-600 hover:text-orange-800 font-semibold">
                                Editar
                            </a>

                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-4">
            {{ $productos->links() }}
        </div>

    </div>

</div>

@endsection
