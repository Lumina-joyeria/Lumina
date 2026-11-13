<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        
        return view('productos.index', [
            'productos' => $productos
        ]);
    }
    
    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        
        return view('productos.create', [
            'categorias' => $categorias
        ]);
    }
    
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'precio' => 'required|numeric|min:0|max:99999999.99',
            'stock' => 'required|integer|min:0',
            'id_categoria' => 'required|integer|exists:categorias,id',
        ]);
        
        Producto::create($datos);
        
        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }
    
    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        
        return view('productos.edit', [
            'producto' => $producto,
            'categorias' => $categorias
        ]);
    }
    
    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'precio' => 'required|numeric|min:0|max:99999999.99',
            'stock' => 'required|integer|min:0',
            'id_categoria' => 'required|integer|exists:categorias,id',
        ]);
        
        $producto->update($datos);
        
        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }
    
    public function destroy(Producto $producto)
    {
        $producto->delete();
        
        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

}
