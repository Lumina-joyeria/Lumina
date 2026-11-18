<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        
        return view('clientes.index', [
            'clientes' => $clientes,
        ]);
    }
    
    public function create()
    {
        return view('clientes.create');
    }
    
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|max:100|unique:clientes,correo',
            'telefono' => 'nullable|string|max:20',
        ]);
        
        Cliente::create($datos);
        
        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }
    
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', [
            'cliente' => $cliente,
        ]);
    }
    
    public function update(Request $request, Cliente $cliente)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => [
                'required',
                'email',
                'max:100',
                Rule::unique('clientes', 'correo')->ignore($cliente->id),
            ],
            'telefono' => 'nullable|string|max:20',
        ]);
        
        $cliente->update($datos);
        
        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }
    
    public function destroy(Cliente $cliente)
    {
        if ($cliente->pedidos()->exists()) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'No se puede eliminar este cliente porque tiene pedidos registrados.');
        }

        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}