<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\ProductoRequest;
use Illuminate\Http\Request;   // ← FALTA ESTO

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::buscar(request('buscar'))
            ->orderBy('descripcion')
            ->paginate(15);

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto creado con éxito.');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado.');
    }

    // ⭐ AUTOCOMPLETAR (API)
    public function buscar(Request $request)
    {
        $q = $request->q;

        return Producto::where('descripcion', 'ILIKE', "%$q%")
            ->orWhere('codigo', 'ILIKE', "%$q%")
            ->limit(10)
            ->get(['id','codigo','descripcion','precio_1','iva']);
    }
}
