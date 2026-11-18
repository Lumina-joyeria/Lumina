<?php

namespace App\Http\Controllers;

use App\Models\MovimientoFinanciero;
use Illuminate\Http\Request;

class MovimientoFinancieroController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoFinanciero::orderByDesc('id')->get();
        
        $ingresos = MovimientoFinanciero::where('tipo', 'Ingreso')
            ->sum('monto');
        
        $gastos = MovimientoFinanciero::where('tipo', 'Gasto')
            ->sum('monto');
        
        $balance = $ingresos - $gastos;
        
        return view('finanzas.index', compact(
            'movimientos',
            'ingresos',
            'gastos',
            'balance'
        ));
    }
    
    public function store(Request $request)
    {
        $datos = $request->validate([
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|gt:0|decimal:0,2',
        ]);
        
        MovimientoFinanciero::create([
            'tipo' => 'Gasto',
            'concepto' => $datos['concepto'],
            'monto' => $datos['monto'],
            'id_pedido' => null,
        ]);
        
        return redirect()
            ->route('finanzas.index')
            ->with('success', 'Gasto registrado correctamente.');
    }
}