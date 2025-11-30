<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-100">
            Lotes enviados a SIFEN
        </h2>
    </x-slot>

    <div class="bg-gray-900 p-6 rounded-xl shadow-lg border border-gray-700">

        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-800 text-gray-200 text-left">
                    <th class="px-3 py-2 border border-gray-700">ID</th>
                    <th class="px-3 py-2 border border-gray-700">Número de lote</th>
                    <th class="px-3 py-2 border border-gray-700">Cantidad</th>
                    <th class="px-3 py-2 border border-gray-700">Estado</th>
                    <th class="px-3 py-2 border border-gray-700">Protocolo</th>
                    <th class="px-3 py-2 border border-gray-700">Acciones</th>
                </tr>
            </thead>

            <tbody class="text-gray-300">
                @foreach($lotes as $lote)
                <tr class="hover:bg-gray-800 transition">
                    <td class="border border-gray-700 px-3 py-2">{{ $lote->id }}</td>
                    <td class="border border-gray-700 px-3 py-2">{{ $lote->numero_lote }}</td>
                    <td class="border border-gray-700 px-3 py-2">{{ $lote->cantidad }}</td>

                    <td class="border border-gray-700 px-3 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($lote->estado == 'pendiente') bg-yellow-500/20 text-yellow-300
                            @elseif($lote->estado == 'enviado') bg-green-500/20 text-green-300
                            @else bg-red-500/20 text-red-300
                            @endif">
                            {{ ucfirst($lote->estado) }}
                        </span>
                    </td>

                    <td class="border border-gray-700 px-3 py-2">
                        {{ $lote->protocolo ?? '-' }}
                    </td>

                    <td class="border border-gray-700 px-3 py-2 space-x-4">

                        <a href="{{ route('lotes.show', $lote->id) }}"
                           class="text-indigo-400 hover:text-indigo-300 font-medium">
                            Ver
                        </a>

                        <a href="{{ route('lotes.xml', $lote->id) }}"
                           class="text-purple-400 hover:text-purple-300 font-medium">
                            XML
                        </a>

                        <a href="{{ route('lotes.consultar', $lote->id) }}"
                           class="text-orange-400 hover:text-orange-300 font-medium">
                            Consultar
                        </a>

                        <form action="{{ route('lotes.enviar', $lote->id) }}"
                              method="POST" class="inline">
                            @csrf
                            <button class="text-green-400 hover:text-green-300 font-medium">
                                Enviar
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-gray-300">
            {{ $lotes->links() }}
        </div>

    </div>
</x-app-layout>
