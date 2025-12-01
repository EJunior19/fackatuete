<x-guest-layout>

    <div class="max-w-md mx-auto mt-10 mb-10">

        {{-- LOGO Y TÍTULO --}}
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/fackatuete/logo-login.svg') }}"
                 alt="Logo FACKATUETE"
                 class="h-12 w-auto mb-3">

            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                Confirmar contraseña
            </h1>

            <p class="text-sm text-gray-500 mt-1 text-center">
                Por seguridad, ingresá tu contraseña para continuar.
            </p>
        </div>

        {{-- TARJETA --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl px-6 py-5">

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                {{-- PASSWORD --}}
                <div>
                    <x-input-label for="password" :value="__('Contraseña')" />

                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" 
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- BOTÓN --}}
                <div class="flex justify-end mt-6">
                    <x-primary-button>
                        Confirmar
                    </x-primary-button>
                </div>

            </form>
        </div>

        {{-- PIE --}}
        <div class="mt-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} FACKATUETE · Sistema de Facturación Electrónica
        </div>

    </div>

</x-guest-layout>
