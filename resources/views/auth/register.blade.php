<x-guest-layout>

    <div class="max-w-md mx-auto mt-10 mb-10">

        {{-- LOGO + TÍTULO --}}
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/fackatuete/logo-login.svg') }}"
                 alt="Logo FACKATUETE"
                 class="h-12 w-auto mb-3">

            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                Crear una cuenta
            </h1>

            <p class="text-sm text-gray-500 mt-1 text-center">
                Registrá un nuevo usuario en el sistema.
            </p>
        </div>

        {{-- TARJETA --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl px-6 py-6">

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- NOMBRE --}}
                <div>
                    <x-input-label for="name" :value="'Nombre completo'" />
                    <x-text-input 
                        id="name"
                        class="block mt-1 w-full"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                {{-- EMAIL --}}
                <div class="mt-4">
                    <x-input-label for="email" :value="'Correo electrónico'" />
                    <x-text-input 
                        id="email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- CONTRASEÑA --}}
                <div class="mt-4">
                    <x-input-label for="password" :value="'Contraseña'" />
                    <x-text-input 
                        id="password"
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- CONFIRMAR CONTRASEÑA --}}
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="'Confirmar contraseña'" />
                    <x-text-input 
                        id="password_confirmation"
                        class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                {{-- FOOTER DEL FORM --}}
                <div class="flex items-center justify-between mt-6">

                    <a class="text-sm text-gray-600 hover:text-gray-900 transition"
                       href="{{ route('login') }}">
                        ¿Ya tenés una cuenta?
                    </a>

                    <x-primary-button class="ml-4">
                        Registrar
                    </x-primary-button>
                </div>

            </form>

        </div>

        {{-- PIE --}}
        <p class="text-center text-xs text-gray-400 mt-4">
            &copy; {{ date('Y') }} FACKATUETE · Sistema de Facturación Electrónica
        </p>

    </div>

</x-guest-layout>
