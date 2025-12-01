<x-guest-layout>

    <div class="max-w-md mx-auto mt-10 mb-10">

        {{-- LOGO Y TÍTULO --}}
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/fackatuete/logo-login.svg') }}"
                 alt="Logo FACKATUETE"
                 class="h-12 w-auto mb-3">

            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                Recuperar contraseña
            </h1>

            <p class="text-sm text-gray-500 mt-1 text-center">
                Ingresá tu correo y te enviaremos un enlace para restaurarla
            </p>
        </div>

        {{-- TARJETA --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl px-6 py-5">

            {{-- ESTADO DE SESIÓN --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <x-input-label for="email" :value="__('Correo electrónico')" />

                    <x-text-input 
                        id="email" 
                        class="block mt-1 w-full"
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                    />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- BOTÓN --}}
                <div class="flex items-center justify-end mt-6">
                    <x-primary-button>
                        Enviar enlace de recuperación
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
