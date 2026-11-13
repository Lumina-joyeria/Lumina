<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        
        return view('categorias.index', [
            'categorias' => $categorias
        ]);
    }
    
    public function create()
    {
        return view('categorias.create');
    }
    
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:50', 'unique:categorias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);
        
        Categoria::create($datos);
        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría registrada correctamente');
    }
    
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', [
            'categoria' => $categoria
        ]);
    }
    
    public function update(Request $request, Categoria $categoria)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);
        $categoria->update($datos);
    return redirect()
        ->route('categorias.index')
        ->with('success', 'Categoría actualizada correctamente.');
    }
    
    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return redirect()
                ->route('categorias.index')
                ->with('error', 'No se puede eliminar una categoría que tiene productos asociados.');
        }
        
        $categoria->delete();
        
        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
