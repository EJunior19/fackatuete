@extends('layouts.app')

@section('content')

<div class="px-6 py-4">

    <h1 class="text-2xl font-bold text-slate-800 mb-4">
        Usuarios de la Empresa
    </h1>

    <a href="{{ route('usuarios.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
        + Crear Usuario
    </a>

    <div class="mt-6 bg-white shadow-md rounded-xl border border-slate-200 p-4">

        <table class="w-full">
            <thead>
                <tr class="text-left text-slate-500 border-b">
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $u)
                    <tr class="border-b">
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ ucfirst($u->role) }}</td>
                        <td>{{ $u->status ? 'Activo' : 'Inactivo' }}</td>
                        <td class="text-right">
                            <a href="{{ route('usuarios.edit', $u) }}" class="text-indigo-600">Editar</a>
                            |
                            <form action="{{ route('usuarios.destroy', $u) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

@endsection
