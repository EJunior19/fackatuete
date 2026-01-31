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

<body class="bg-gray-100 text-gray-800 antialiased overflow-hidden">

<div class="min-h-screen h-screen flex">


    {{-- ================= SIDEBAR DESKTOP ================= --}}
    <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200 shadow-sm">

        {{-- Logo --}}
        <div class="h-16 flex items-center justify-center border-b border-gray-200 shrink-0">
            <h1 class="text-lg font-semibold text-gray-800 tracking-wide">
                FacKatuete
            </h1>
        </div>

        {{-- MENU (SCROLL INDEPENDIENTE) --}}
        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1 text-sm">
            @include('partials.sidebar-links')
        </nav>

    </aside>

    {{-- ================= SIDEBAR MÓVIL ================= --}}
    <div class="fixed inset-0 z-40 lg:hidden" x-show="sidebarOpen" x-transition.opacity>
        <div class="absolute inset-0 bg-black bg-opacity-30" @click="sidebarOpen = false"></div>

        <aside class="relative w-64 h-full bg-white border-r border-gray-200 shadow-sm flex flex-col">

            <div class="h-16 flex items-center justify-between border-b border-gray-200 px-4 shrink-0">
                <h1 class="text-lg font-semibold text-gray-800">FacKatuete</h1>
                <button class="text-gray-500 hover:text-gray-700" @click="sidebarOpen = false">✕</button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1 text-sm">
                @include('partials.sidebar-links')
            </nav>

        </aside>
    </div>

    {{-- ================= MAIN ================= --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- TOPBAR (FIJA) --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 shadow-sm shrink-0">
            <button
                class="lg:hidden text-gray-600 hover:text-gray-900"
                @click="sidebarOpen = true">
                ☰
            </button>

            <div class="text-sm text-gray-700 ml-auto">
                Bienvenido, <span class="font-semibold">{{ Auth::user()->name }}</span>
            </div>
        </header>

        {{-- CONTENIDO (SCROLL SOLO AQUÍ) --}}
        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

</div>

@yield('scripts')

</body>
</html>
