<x-guest-layout>

    <div class="max-w-md mx-auto mt-10 mb-10">

        {{-- LOGO + TÍTULO --}}
        <div class="flex flex-col items-center mb-6">
            {{-- Logo de la app (cuando lo tengas creado, guardalo en public/images/fackatuete/) --}}
            <div class="mb-3">
                <img src="{{ asset('images/fackatuete/logo-login.svg') }}"
                     alt="Logo FACKATUETE"
                     class="h-12 w-auto">
            </div>

            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                FACKATUETE
            </h1>
            <p class="text-sm text-gray-500 mt-1 text-center">
                Sistema de Facturación Electrónica
            </p>
        </div>

        {{-- TARJETA DEL LOGIN --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl px-6 py-5">

            {{-- Mensaje de estado de sesión --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <x-input-label for="email" :value="__('Correo electrónico')" />

                    <x-text-input id="email"
                                  class="block mt-1 w-full"
                                  type="email"
                                  name="email"
                                  :value="old('email')"
                                  required
                                  autofocus
                                  autocomplete="username" />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- PASSWORD --}}
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />

                    <x-text-input id="password"
                                  class="block mt-1 w-full"
                                  type="password"
                                  name="password"
                                  required
                                  autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- RECORDARME --}}
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me"
                               type="checkbox"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               name="remember">
                        <span class="ms-2 text-sm text-gray-600">
                            {{ __('Recordarme en este dispositivo') }}
                        </span>
                    </label>
                </div>

                {{-- ENLACES + BOTÓN --}}
                <div class="flex items-center justify-between mt-6">

                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                           href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3">
                        {{ __('Ingresar') }}
                    </x-primary-button>
                </div>

            </form>
        </div>

        {{-- PIE (OPCIONAL) --}}
        <div class="mt-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} FACKATUETE · Todos los derechos reservados
        </div>

    </div>

</x-guest-layout>
