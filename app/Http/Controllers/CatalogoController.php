<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Producto;
use App\Models\Categoria;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
            'categorias' => ['nullable', 'array'],
            'categorias.*' => ['integer', 'distinct', 'min:1'],
            'orden' => ['nullable', 'in:recientes,precio_asc,precio_desc'],
        ]);

        $buscar = $filtros['buscar'] ?? null;
        $categoriasSeleccionadas = $filtros['categorias'] ?? [];
        $orden = $filtros['orden'] ?? 'recientes';

        $consulta = Producto::with('categoria');

        $consulta->when($buscar, function ($query) use ($buscar) {
            $query->where('nombre', 'like', '%' . $buscar . '%');
        });

        $consulta->when(
            count($categoriasSeleccionadas) > 0,
            function ($query) use ($categoriasSeleccionadas) {
                $query->whereIn('id_categoria', $categoriasSeleccionadas);
            }
        );

        switch ($orden) {
            case 'precio_asc':
                $consulta->orderBy('precio')->orderBy('id');
                break;

            case 'precio_desc':
                $consulta->orderByDesc('precio')->orderByDesc('id');
                break;

            default:
                $consulta->orderByDesc('id');
                break;
        }

        $productos = $consulta->get();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('store.catalog', compact(
            'productos',
            'categorias'
        ));
    }
    
    public function show(Producto $producto)
    {
        $producto->load('categoria');
        
        return view('store.product', compact('producto'));
    }
}
