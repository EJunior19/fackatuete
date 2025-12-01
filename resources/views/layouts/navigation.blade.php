<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">

            <div class="flex items-center">
                <!-- Logo / Nombre sistema -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        {{-- Si querés, podés cambiar este img por tu svg real --}}
                        <img src="{{ asset('images/fackatuete-logo.svg') }}"
                             alt="FACKATUETE"
                             class="h-7 w-auto">
                        <span class="text-sm font-semibold text-gray-100 tracking-wide">
                            FACKATUETE
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex text-sm">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('documentos.index')" :active="request()->is('documentos*')" class="text-gray-300">
                        Documentos
                    </x-nav-link>

                    <x-nav-link :href="route('clientes.index')" :active="request()->is('clientes*')" class="text-gray-300">
                        Clientes
                    </x-nav-link>

                    <x-nav-link :href="route('productos.index')" :active="request()->is('productos*')" class="text-gray-300">
                        Productos
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    {{-- Trigger --}}
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-1.5 border border-slate-700 text-sm leading-4 font-medium rounded-md text-gray-200 bg-slate-800 hover:bg-slate-700 hover:text-white focus:outline-none focus:ring-1 focus:ring-sky-500 transition">
                            <div class="mr-2 text-xs text-gray-400 uppercase">Usuario</div>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    {{-- Dropdown Content --}}
                    <x-slot name="content">
                        <div class="px-3 py-2 text-xs text-gray-500">
                            Sesión de {{ Auth::user()->email }}
                        </div>

                        <div class="border-t border-slate-700 my-1"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                class="text-red-400 hover:text-red-300"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-slate-800 focus:outline-none focus:bg-slate-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path
                            :class="{ 'hidden': open, 'inline-flex': ! open }"
                            class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path
                            :class="{ 'hidden': ! open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden bg-slate-900 border-t border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('documentos.index')" :active="request()->is('documentos*')">
                Documentos
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('clientes.index')" :active="request()->is('clientes*')">
                Clientes
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('productos.index')" :active="request()->is('productos*')">
                Productos
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-800">
            <div class="px-4">
                <div class="font-medium text-base text-gray-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Logout Responsive -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link
                        :href="route('logout')"
                        class="text-red-400"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
