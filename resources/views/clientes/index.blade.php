<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-100">Clientes</h1>
    </x-slot>

    <div class="bg-gray-900 border border-gray-700 p-6 rounded-lg shadow">

        {{-- BUSCADOR + NUEVO --}}
        <div class="flex justify-between items-center mb-4">

            <form method="GET" class="flex space-x-2">
                <input 
                    type="text" 
                    name="buscar" 
                    placeholder="Buscar RUC o Nombre..."
                    value="{{ request('buscar') }}"
                    class="bg-gray-800 border border-gray-700 text-gray-200 px-3 py-2 rounded 
                           focus:border-blue-500 focus:ring-blue-500 focus:ring-1"
                >

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                    Buscar
                </button>
            </form>

            <a href="{{ route('clientes.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition">
                + Nuevo Cliente
            </a>
        </div>

        {{-- TABLA --}}
        <table class="w-full text-sm border-collapse text-gray-300">
            <thead>
                <tr class="bg-gray-800 text-gray-300">
                    <th class="border border-gray-700 px-3 py-2 text-left">RUC</th>
                    <th class="border border-gray-700 px-3 py-2 text-left">Razón Social</th>
                    <th class="border border-gray-700 px-3 py-2 text-left">Teléfono</th>
                    <th class="border border-gray-700 px-3 py-2 text-left">Email</th>
                    <th class="border border-gray-700 px-3 py-2 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($clientes as $cli)
                <tr class="hover:bg-gray-800 transition">
                    <td class="border border-gray-700 px-3 py-2">{{ $cli->ruc }}-{{ $cli->dv }}</td>
                    <td class="border border-gray-700 px-3 py-2">{{ $cli->razon_social }}</td>
                    <td class="border border-gray-700 px-3 py-2">{{ $cli->telefono }}</td>
                    <td class="border border-gray-700 px-3 py-2">{{ $cli->email }}</td>

                    <td class="border border-gray-700 px-3 py-2 text-center space-x-3 flex justify-center">

                        {{-- EDITAR --}}
                        <a href="{{ route('clientes.edit', $cli->id) }}" 
                           class="text-blue-400 hover:text-blue-300">
                            Editar
                        </a>

                        {{-- ELIMINAR --}}
                        <x-confirm-delete 
                            :action="route('clientes.destroy', $cli->id)" 
                            message="¿Seguro que desea eliminar este cliente?"
                        />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- PAGINACIÓN --}}
        <div class="mt-4 text-gray-400">
            {{ $clientes->links() }}
        </div>

    </div>
</x-app-layout>
