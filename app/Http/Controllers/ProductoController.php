<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;

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
            'id_categoria' => 'required|integer|exists:categorias,id',
            'imagen' => 'required|image|mimetypes:image/jpeg,image/png,image/webp|max:5120',
        ]);
        
        $datos['stock'] = 0;
        
        $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        
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
            'id_categoria' => 'required|integer|exists:categorias,id',
            'imagen' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp|max:5120',
        ]);
        
        $imagenAnterior = $producto->imagen;
        
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        
        $producto->update($datos);
        
        if (
            isset($datos['imagen']) &&
            $imagenAnterior &&
            $imagenAnterior !== $datos['imagen']
        ) {
            Storage::disk('public')->delete($imagenAnterior);
        }
        
        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }
    
    public function destroy(Producto $producto)
    {
        if (
            $producto->detalles()->exists() ||
            $producto->comprasInventario()->exists()
        ) {
            return redirect()
                ->route('productos.index')
                ->with('error', 'No se puede eliminar este producto porque tiene pedidos o compras de inventario registrados.');
        }
        
        $producto->delete();
        
        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
