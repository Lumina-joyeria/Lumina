<?php

namespace App\Http\Controllers;

use App\Models\CompraInventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraInventarioController extends Controller
{
    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();

        return view('compras.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'id_producto' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer|min:1|max:1000',
            'costo_unitario' => 'required|numeric|min:0.01|max:99999|decimal:0,2',
        ]);

        DB::transaction(function () use ($datos) {

            $producto = Producto::whereKey($datos['id_producto'])
                ->lockForUpdate()
                ->firstOrFail();

            $cantidad = (int) $datos['cantidad'];

            $costoCentavos = (int) round(
                (float) $datos['costo_unitario'] * 100
            );

            $total = number_format(
                ($costoCentavos * $cantidad) / 100,
                2,
                '.',
                ''
            );

            $idMovimiento = DB::table('movimientos_financieros')
                ->insertGetId([
                    'tipo' => 'Gasto',
                    'concepto' => "Compra de inventario: {$producto->nombre}",
                    'monto' => $total,
                    'id_pedido' => null,
                ]);

            CompraInventario::create([
                'id_producto' => $producto->id,
                'id_movimiento' => $idMovimiento,
                'cantidad' => $cantidad,
                'costo_unitario' => $datos['costo_unitario'],
                'total' => $total,
            ]);

            $producto->increment('stock', $cantidad);
        });

        return redirect()
            ->route('compras.create')
            ->with('success', 'Compra registrada e inventario actualizado.');
    }
}
