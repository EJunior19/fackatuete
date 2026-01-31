<a href="{{ route('dashboard') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
   {{ request()->routeIs('dashboard')
        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>📊</span> <span>Dashboard</span>
</a>

<a href="{{ route('documentos.index') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
   {{ request()->is('documentos*')
        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>🧾</span> <span>Documentos</span>
</a>

<a href="{{ route('clientes.index') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
   {{ request()->is('clientes*')
        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>👥</span> <span>Clientes</span>
</a>

<a href="{{ route('productos.index') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
   {{ request()->is('productos*')
        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>📦</span> <span>Productos</span>
</a>

<a href="{{ route('lotes.index') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
   {{ request()->is('lotes*')
        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>📤</span> <span>Lotes SIFEN</span>
</a>

<a href="{{ route('eventos.index') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
   {{ request()->is('eventos*')
        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>🧩</span> <span>Eventos</span>
</a>

<a href="{{ route('config.empresa') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
   {{ request()->is('config*')
        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>⚙️</span> <span>Configuración</span>
</a>

<a href="{{ route('usuarios.index') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-md border-l-4 transition
   {{ request()->is('usuarios*')
        ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold'
        : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>🧑‍💼</span> <span>Usuarios</span>
</a>

<form method="POST" action="{{ route('logout') }}" class="pt-4 mt-4 border-t border-gray-200">
    @csrf
    <button
        class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-sm
               text-red-600 hover:bg-red-50 hover:text-red-700 transition">
        <span>🔒</span> <span>Cerrar sesión</span>
    </button>
</form>
