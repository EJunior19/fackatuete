<?php

namespace App\Http\Controllers\Sifen;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request; // ← FALTA ESTO

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('razon_social')->paginate(15);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruc'          => 'required|digits_between:3,15',
            'dv'           => 'required|digits_between:1,2',
            'razon_social' => 'required|string|max:150',
            'telefono'     => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:120',
            'direccion'    => 'nullable|string|max:255',
        ]);

        Cliente::create($request->all());

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'ruc'          => 'required|digits_between:3,15',
            'dv'           => 'required|digits_between:1,2',
            'razon_social' => 'required|string|max:150',
            'telefono'     => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:120',
            'direccion'    => 'nullable|string|max:255',
        ]);

        $cliente->update($request->all());

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }

    // ⭐ AUTOCOMPLETADO DE CLIENTES (API)
    public function buscar(Request $request)
    {
        $q = $request->q;

        return Cliente::where('ruc', 'ILIKE', "%$q%")
            ->orWhere('razon_social', 'ILIKE', "%$q%")
            ->limit(10)
            ->get(['id','ruc','dv','razon_social','telefono','email','direccion']);
    }
}
