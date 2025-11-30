@props(['action', 'message' => '¿Seguro que desea eliminar este registro?'])

<div 
    x-data="{ open: false }"
    class="inline"
>
    <!-- Botón de eliminar -->
    <button 
        @click="open = true"
        class="text-red-600 hover:text-red-800"
    >
        Eliminar
    </button>

    <!-- MODAL -->
    <div 
        x-show="open"
        style="display:none"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg shadow-xl p-6 w-80">

            <h2 class="text-lg font-semibold text-center mb-2">
                Confirmar eliminación
            </h2>

            <p class="text-center text-gray-600 mb-4">
                {{ $message }}
            </p>

            <div class="flex justify-between mt-4">
                <button 
                    @click="open = false"
                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300"
                >
                    Cancelar
                </button>

                <form method="POST" action="{{ $action }}">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
