<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'FacKatuete'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800 antialiased">

<div class="min-h-screen flex">

    {{-- SIDEBAR DESKTOP --}}
    <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200 shadow-sm">

        <div class="h-16 flex items-center justify-center border-b border-gray-200">
            <h1 class="text-lg font-semibold text-gray-800 tracking-wide">
                FacKatuete
            </h1>
        </div>

        <nav class="px-4 py-4 space-y-1 text-sm flex-1">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                    {{ request()->routeIs('dashboard')
                        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>📊</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('documentos.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                    {{ request()->is('documentos*')
                        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>🧾</span>
                <span>Documentos</span>
            </a>

            <a href="{{ route('clientes.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                    {{ request()->is('clientes*')
                        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>👥</span>
                <span>Clientes</span>
            </a>

            <a href="{{ route('productos.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                    {{ request()->is('productos*')
                        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>📦</span>
                <span>Productos</span>
            </a>

            <a href="{{ route('lotes.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                    {{ request()->is('lotes*')
                        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>📤</span>
                <span>Lotes SIFEN</span>
            </a>

            <a href="{{ route('eventos.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                    {{ request()->is('eventos*')
                        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>🧩</span>
                <span>Eventos</span>
            </a>

            <a href="{{ route('config.empresa') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                    {{ request()->is('config*')
                        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>⚙️</span>
                <span>Configuración</span>
            </a>

            <a href="{{ route('usuarios.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                    {{ request()->is('usuarios*')
                        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <span>🧑‍💼</span>
                <span>Usuarios</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="pt-4 mt-4 border-t border-gray-200">
                @csrf
                <button
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition">
                    <span>🔒</span>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </nav>

    </aside>

    {{-- SIDEBAR MÓVIL --}}
    <div class="fixed inset-0 z-40 lg:hidden" x-show="sidebarOpen" x-transition.opacity>
        <div class="absolute inset-0 bg-black bg-opacity-30" @click="sidebarOpen = false"></div>

        <aside class="relative w-64 h-full bg-white border-r border-gray-200 shadow-sm">
            <div class="h-16 flex items-center justify-between border-b border-gray-200 px-4">
                <h1 class="text-lg font-semibold text-gray-800">FacKatuete</h1>
                <button class="text-gray-500 hover:text-gray-700" @click="sidebarOpen = false">✕</button>
            </div>

            <nav class="px-4 py-4 space-y-1 text-sm">
                {{-- reutilizamos exactamente las mismas clases que arriba --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                        {{ request()->routeIs('dashboard')
                            ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                            : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('documentos.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                        {{ request()->is('documentos*')
                            ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                            : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>🧾</span>
                    <span>Documentos</span>
                </a>

                <a href="{{ route('clientes.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                        {{ request()->is('clientes*')
                            ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                            : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>👥</span>
                    <span>Clientes</span>
                </a>

                <a href="{{ route('productos.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                        {{ request()->is('productos*')
                            ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                            : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>📦</span>
                    <span>Productos</span>
                </a>

                <a href="{{ route('lotes.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                        {{ request()->is('lotes*')
                            ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                            : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>📤</span>
                    <span>Lotes SIFEN</span>
                </a>

                <a href="{{ route('eventos.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                        {{ request()->is('eventos*')
                            ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                            : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>🧩</span>
                    <span>Eventos</span>
                </a>

                <a href="{{ route('config.empresa') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                        {{ request()->is('config*')
                            ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                            : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>⚙️</span>
                    <span>Configuración</span>
                </a>

                <a href="{{ route('usuarios.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
                        {{ request()->is('usuarios*')
                            ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
                            : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>🧑‍💼</span>
                    <span>Usuarios</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="pt-4 mt-4 border-t border-gray-200">
                    @csrf
                    <button
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition">
                        <span>🔒</span>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </nav>
        </aside>
    </div>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col">

        {{-- TOPBAR --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 shadow-sm">
            <button
                class="lg:hidden text-gray-600 hover:text-gray-900"
                @click="sidebarOpen = true">
                ☰
            </button>

            <div class="text-sm text-gray-700 ml-auto">
                Bienvenido, <span class="font-semibold">{{ Auth::user()->name }}</span>
            </div>
        </header>

        {{-- CONTENIDO --}}
        <main class="p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

</div>

@yield('scripts')

</body>
</html>
