<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-950">
        <div class="w-full max-w-md px-4 sm:px-6 py-8">

            {{-- LOGO + TÍTULO --}}
            <div class="flex flex-col items-center mb-6">
                <div class="mb-4 rounded-full bg-slate-900 border border-emerald-400/70 p-3 shadow-lg shadow-emerald-500/20">
                    <img src="{{ asset('images/fackatuete/logo-fackatuete.png') }}"
                         alt="Logo FACKATUETE"
                         class="h-10 w-auto">
                </div>

                <h1 class="text-2xl font-semibold tracking-tight text-emerald-400">
                    FACKATUETE
                </h1>
                <p class="text-sm text-slate-300 mt-1 text-center">
                    Sistema de Facturación Electrónica
                </p>
            </div>

            {{-- TARJETA DEL LOGIN --}}
            <div class="bg-slate-900/90 border border-slate-700 rounded-2xl px-6 py-6 shadow-xl shadow-black/40 backdrop-blur">

                {{-- Mensaje de estado de sesión --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- EMAIL --}}
                    <div>
                        <x-input-label for="email" :value="__('Correo electrónico')" class="text-slate-200" />

                        <x-text-input id="email"
                                      class="block mt-1 w-full bg-slate-800 border-slate-700 text-slate-100 focus:border-emerald-400 focus:ring-emerald-400"
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
                        <x-input-label for="password" :value="__('Contraseña')" class="text-slate-200" />

                        <x-text-input id="password"
                                      class="block mt-1 w-full bg-slate-800 border-slate-700 text-slate-100 focus:border-emerald-400 focus:ring-emerald-400"
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
                                   class="rounded border-slate-600 bg-slate-800 text-emerald-400 shadow-sm focus:ring-emerald-400"
                                   name="remember">
                            <span class="ms-2 text-sm text-slate-300">
                                {{ __('Recordarme en este dispositivo') }}
                            </span>
                        </label>
                    </div>

                    {{-- ENLACES + BOTÓN --}}
                    <div class="flex items-center justify-between mt-6">

                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-slate-400 hover:text-emerald-400 transition-colors rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400 focus:ring-offset-slate-900"
                               href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3 bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-semibold border-0 focus:ring-emerald-400">
                            {{ __('Ingresar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- PIE --}}
            <div class="mt-6 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} FACKATUETE · Todos los derechos reservados
            </div>

        </div>
    </div>
</x-guest-layout>
