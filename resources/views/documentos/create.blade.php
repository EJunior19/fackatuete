<x-app-layout>

    <!-- Header del módulo -->
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-100">
            🧾 Nuevo Documento Electrónico
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-6 bg-gray-800 text-gray-100 rounded-xl shadow-lg border border-gray-700">

        <!-- Contenedor -->
        <div class="p-6 space-y-5">

            <!-- Título interno -->
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Crear Documento</h3>

                <a href="{{ route('documentos.index') }}"
                   class="text-sm text-gray-300 hover:text-white transition">
                    ← Volver a Documentos
                </a>
            </div>

            <!-- Formulario -->
            <div class="bg-gray-900 p-6 rounded-lg border border-gray-700">
                @include('documentos._form', [
                    'action' => route('documentos.store'),
                    'method' => 'POST',
                    'buttonText' => 'Guardar Documento'
                ])
            </div>

        </div>

    </div>

</x-app-layout>
