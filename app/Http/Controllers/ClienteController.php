<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::withExists('pedidos')
            ->orderBy('nombre')
            ->get();

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
        Cliente::create($this->validarDatos($request));

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
        $cliente->update($this->validarDatos($request, $cliente));

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

    private function validarDatos(Request $request, ?Cliente $cliente = null): array
    {
        $correoUnico = Rule::unique('clientes', 'correo');

        if ($cliente) {
            $correoUnico->ignore($cliente);
        }

        return $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'correo' => ['required', 'email', 'max:100', $correoUnico],
            'telefono' => ['nullable', 'string', 'max:20'],
        ], [
            'nombre.required' => 'Escribe el nombre del cliente.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre admite hasta :max caracteres.',
            'correo.required' => 'Escribe el correo del cliente.',
            'correo.email' => 'Escribe un correo electrónico válido.',
            'correo.max' => 'El correo admite hasta :max caracteres.',
            'correo.unique' => 'Ya existe un cliente con este correo.',
            'telefono.string' => 'El teléfono debe ser texto.',
            'telefono.max' => 'El teléfono admite hasta :max caracteres.',
        ]);
    }
}
