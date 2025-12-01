@extends('layouts.app')

@section('title', 'Eventos del SIFEN')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            Eventos del SIFEN
        </h2>
    </div>

    {{-- CARD PRINCIPAL --}}
    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">

        {{-- TABLA --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border border-gray-200 px-3 py-2 text-left">Código</th>
                        <th class="border border-gray-200 px-3 py-2 text-left">Tipo</th>
                        <th class="border border-gray-200 px-3 py-2 text-left">Descripción</th>
                        <th class="border border-gray-200 px-3 py-2 text-left">Fecha</th>
                        <th class="border border-gray-200 px-3 py-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">

                    @foreach($eventos as $ev)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="border border-gray-200 px-3 py-2">
                            {{ $ev->codigo }}
                        </td>

                        <td class="border border-gray-200 px-3 py-2">
                            {{ ucfirst($ev->tipo) }}
                        </td>

                        <td class="border border-gray-200 px-3 py-2">
                            {{ $ev->descripcion }}
                        </td>

                        <td class="border border-gray-200 px-3 py-2">
                            {{ $ev->created_at }}
                        </td>

                        <td class="border border-gray-200 px-3 py-2 text-center">
                            <a href="{{ route('eventos.show', $ev->id) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Ver detalle
                            </a>
                        </td>

                    </tr>
                    @endforeach

                    @if($eventos->count() == 0)
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            No hay eventos registrados.
                        </td>
                    </tr>
                    @endif

                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-4 text-gray-600">
            {{ $eventos->links() }}
        </div>

    </div>

</div>

@endsection
