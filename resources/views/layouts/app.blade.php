<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-900 text-gray-200">

    <!-- Overlay móvil -->
    <div 
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false">
    </div>

    <!-- SIDEBAR -->
    <aside 
        class="fixed z-50 inset-y-0 left-0 w-64 bg-gray-800 border-r border-gray-700 transform lg:translate-x-0 transition-transform duration-200"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <div class="h-16 flex items-center justify-center border-b border-gray-700">
            <h1 class="text-xl font-bold text-white">FACKATUETE</h1>
        </div>

        <nav class="px-4 py-4 space-y-2">
            
            <a href="{{ route('dashboard') }}" 
               class="block px-3 py-2 rounded-lg hover:bg-gray-700 transition
                    {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300' }}">
                📊 Dashboard
            </a>

            <a href="{{ route('documentos.index') }}" 
               class="block px-3 py-2 rounded-lg hover:bg-gray-700 transition
                    {{ request()->is('documentos*') ? 'bg-gray-700 text-white' : 'text-gray-300' }}">
                🧾 Documentos
            </a>

            <a href="{{ route('clientes.index') }}" 
               class="block px-3 py-2 rounded-lg hover:bg-gray-700 transition
                    {{ request()->is('clientes*') ? 'bg-gray-700 text-white' : 'text-gray-300' }}">
                👥 Clientes
            </a>

            <a href="{{ route('productos.index') }}" 
               class="block px-3 py-2 rounded-lg hover:bg-gray-700 transition
                    {{ request()->is('productos*') ? 'bg-gray-700 text-white' : 'text-gray-300' }}">
                📦 Productos
            </a>

            <a href="{{ route('lotes.index') }}" 
               class="block px-3 py-2 rounded-lg hover:bg-gray-700 transition
                    {{ request()->is('lotes*') ? 'bg-gray-700 text-white' : 'text-gray-300' }}">
                📤 Lotes SIFEN
            </a>

            <a href="{{ route('eventos.index') }}" 
               class="block px-3 py-2 rounded-lg hover:bg-gray-700 transition
                    {{ request()->is('eventos*') ? 'bg-gray-700 text-white' : 'text-gray-300' }}">
                🧩 Eventos
            </a>

            <a href="{{ route('config.empresa') }}" 
               class="block px-3 py-2 rounded-lg hover:bg-gray-700 transition
                    {{ request()->is('config*') ? 'bg-gray-700 text-white' : 'text-gray-300' }}">
                ⚙️ Configuración
            </a>

            <a href="{{ route('usuarios.index') }}" 
               class="block px-3 py-2 rounded-lg hover:bg-gray-700 transition
                    {{ request()->is('usuarios*') ? 'bg-gray-700 text-white' : 'text-gray-300' }}">
                🧑‍💼 Usuarios
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button 
                    class="w-full text-left block px-3 py-2 rounded-lg text-red-400 hover:bg-gray-700 transition">
                    🔒 Cerrar sesión
                </button>
            </form>

        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="lg:ml-64 min-h-screen flex flex-col">

        <!-- TOPBAR -->
        <header class="h-16 bg-gray-800 border-b border-gray-700 flex items-center justify-between px-4">
            <!-- Botón móvil -->
            <button 
                class="lg:hidden text-gray-300 hover:text-white"
                @click="sidebarOpen = true">
                ☰
            </button>

            <div class="text-gray-300">
                Bienvenido, <span class="font-bold">{{ Auth::user()->name }}</span>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
