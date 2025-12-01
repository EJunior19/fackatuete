<x-guest-layout>

    <div class="max-w-md mx-auto mt-10 mb-10">

        {{-- LOGO + TÍTULO --}}
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/fackatuete/logo-login.svg') }}"
                 alt="Logo FACKATUETE"
                 class="h-12 w-auto mb-3">

            <h1 class="text-2xl font-bold text-gray-800 tracking-tight text-center">
                Verificación de correo electrónico
            </h1>

            <p class="text-sm text-gray-500 mt-1 text-center">
                Gracias por registrarte. Antes de continuar, por favor verificá tu correo
                haciendo clic en el enlace que te enviamos.
            </p>
        </div>

        {{-- TARJETA --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl px-6 py-6">

            {{-- Mensaje cuando se reenvía el enlace --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-sm font-medium text-green-600">
                    Se envió un nuevo enlace de verificación al correo electrónico que registraste.
                </div>
            @endif

            <div class="text-sm text-gray-600 mb-4">
                Si no recibiste el correo, podés solicitar otro enlace de verificación.
            </div>

            <div class="mt-4 flex items-center justify-between">

                {{-- Reenviar enlace --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <x-primary-button>
                        Reenviar enlace
                    </x-primary-button>
                </form>

                {{-- Cerrar sesión --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="underline text-sm text-gray-500 hover:text-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </div>

        {{-- FOOTER --}}
        <p class="text-center text-xs text-gray-400 mt-4">
            &copy; {{ date('Y') }} FACKATUETE · Sistema de Facturación Electrónica
        </p>

    </div>

</x-guest-layout>
