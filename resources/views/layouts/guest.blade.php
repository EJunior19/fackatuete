<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FACKATUETE') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-900 text-gray-100">
        <div class="min-h-screen flex items-center justify-center px-4">

            <div class="w-full max-w-md">

                {{-- LOGO + TÍTULO --}}
                <div class="flex flex-col items-center mb-6">
                    <a href="/">
                        {{-- Logo (después podés cambiar el archivo por el logo real) --}}
                        <img src="{{ asset('images/fackatuete-logo.svg') }}"
                             alt="FACKATUETE"
                             class="h-14 w-auto mb-3 opacity-90">
                    </a>

                    <h1 class="text-xl font-semibold tracking-wide text-gray-100">
                        FACKATUETE
                    </h1>
                    <p class="text-xs text-gray-400">
                        Sistema de Facturación Electrónica
                    </p>
                </div>

                {{-- CARD --}}
                <div class="bg-slate-900/90 border border-slate-700 rounded-xl shadow-lg px-6 py-5">
                    {{ $slot }}
                </div>

                {{-- FOOTER PEQUEÑO --}}
                <div class="mt-4 text-center text-xs text-gray-500">
                    &copy; {{ date('Y') }} FACKATUETE · Todos los derechos reservados
                </div>

            </div>

        </div>
    </body>
</html>
