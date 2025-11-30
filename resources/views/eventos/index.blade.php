<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-100">
            Eventos del SIFEN
        </h2>
    </x-slot>

    <div class="bg-gray-900 p-6 rounded-xl shadow-lg border border-gray-700">

        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-800 text-gray-200">
                    <th class="border border-gray-700 px-3 py-2">Código</th>
                    <th class="border border-gray-700 px-3 py-2">Tipo</th>
                    <th class="border border-gray-700 px-3 py-2">Descripción</th>
                    <th class="border border-gray-700 px-3 py-2">Fecha</th>
                    <th class="border border-gray-700 px-3 py-2">Acciones</th>
                </tr>
            </thead>

            <tbody class="text-gray-300">
                @foreach($eventos as $ev)
                <tr class="hover:bg-gray-800 transition">
                    <td class="border border-gray-800 px-3 py-2">{{ $ev->codigo }}</td>
                    <td class="border border-gray-800 px-3 py-2">{{ ucfirst($ev->tipo) }}</td>
                    <td class="border border-gray-800 px-3 py-2">{{ $ev->descripcion }}</td>
                    <td class="border border-gray-800 px-3 py-2">{{ $ev->created_at }}</td>
                    <td class="border border-gray-800 px-3 py-2">
                        <a class="text-indigo-400 hover:text-indigo-300 font-semibold"
                           href="{{ route('eventos.show', $ev->id) }}">
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

        <div class="mt-4 text-gray-300">
            {{ $eventos->links() }}
        </div>

    </div>
</x-app-layout>
