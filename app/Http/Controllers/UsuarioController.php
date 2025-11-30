<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'empresa_id' => auth()->user()->empresa_id,
            'role'       => $request->role,
            'status'     => true,
            'password'   => bcrypt($request->password),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $this->authorizeUser($usuario);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $this->authorizeUser($usuario);

        $usuario->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $usuario)
    {
        $this->authorizeUser($usuario);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }

    private function authorizeUser(User $usuario)
    {
        if ($usuario->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }
    }
}
