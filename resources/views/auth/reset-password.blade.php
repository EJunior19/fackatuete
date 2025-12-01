<x-guest-layout>

    <div class="max-w-md mx-auto mt-10 mb-10">

        {{-- LOGO + TÍTULO --}}
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/fackatuete/logo-login.svg') }}"
                 alt="Logo FACKATUETE"
                 class="h-12 w-auto mb-3">

            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                Restablecer contraseña
            </h1>

            <p class="text-sm text-gray-500 mt-1 text-center">
                Ingresá tu nueva contraseña para continuar.
            </p>
        </div>

        {{-- TARJETA --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl px-6 py-6">

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- Token oculto --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- EMAIL --}}
                <div>
                    <x-input-label for="email" :value="'Correo electrónico'" />

                    <x-text-input 
                        id="email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- PASSWORD --}}
                <div class="mt-4">
                    <x-input-label for="password" :value="'Nueva contraseña'" />

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

                {{-- CONFIRMAR --}}
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="'Confirmar nueva contraseña'" />

                    <x-text-input 
                        id="password_confirmation"
                        class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <x-input-error
                        :messages="$errors->get('password_confirmation')"
                        class="mt-2"
                    />
                </div>

                {{-- BOTÓN --}}
                <div class="flex items-center justify-end mt-6">
                    <x-primary-button>
                        Restablecer Contraseña
                    </x-primary-button>
                </div>

            </form>

        </div>

        {{-- FOOTER --}}
        <p class="text-center text-xs text-gray-400 mt-4">
            &copy; {{ date('Y') }} FACKATUETE · Sistema de Facturación Electrónica
        </p>

    </div>

</x-guest-layout>
