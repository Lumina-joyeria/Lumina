<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\MovimientoFinanciero;

class PedidoController extends Controller
{
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        
        $productos = Producto::where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();
        
        return view('pedidos.create', compact('clientes', 'productos'));
    }
    
    public function store(Request $request)
    {
        $datos = $request->validate([
            'id_cliente' => 'required|integer|exists:clientes,id',
            'items' => 'required|array|min:1',
            'items.*.id_producto' => 'required|integer|distinct|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ], [
            'id_cliente.*' => 'Selecciona un cliente válido.',
            'items.required' => 'Agrega al menos un producto.',
            'items.array' => 'Revisa la lista de productos.',
            'items.min' => 'Agrega al menos un producto.',
            'items.*.id_producto.required' => 'Selecciona un producto.',
            'items.*.id_producto.integer' => 'Selecciona un producto válido.',
            'items.*.id_producto.distinct' => 'Este producto ya está en el pedido. Ajusta su cantidad en una sola fila.',
            'items.*.id_producto.exists' => 'El producto seleccionado ya no está disponible.',
            'items.*.cantidad.*' => 'Ingresa una cantidad entera mayor que cero.',
        ]);
        
        $pedido = DB::transaction(function () use ($datos) {
            
            $pedido = Pedido::create([
                'id_cliente' => $datos['id_cliente'],
                'estado' => 'Pendiente',
            ]);
            
            foreach ($datos['items'] as $item) {
                
                $producto = Producto::findOrFail($item['id_producto']);
                
                if ($producto->stock < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuficiente para {$producto->nombre}.",
                    ]);
                }
                
                DetallePedido::create([
                    'id_pedido' => $pedido->id,
                    'id_producto' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                ]);
            }
            
            return $pedido;
        });
        
        return redirect()
            ->route('pedidos.create')
            ->with('success', "Pedido #{$pedido->id} registrado correctamente.");
    }
    
    public function index()
    {
        $pedidos = Pedido::with(['cliente', 'detalles.producto'])
            ->orderByDesc('id')
            ->get();
            
        return view('pedidos.index', compact('pedidos'));
    }
    
    public function confirmar(int $id)
    {
        DB::transaction(function () use ($id) {
            
            $pedido = Pedido::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();
            
            if ($pedido->estado !== 'Pendiente') {
                throw ValidationException::withMessages([
                    'pedido' => 'Solo se pueden confirmar pedidos pendientes.',
                ]);
                
            }
            
            $detalles = $pedido->detalles()
                ->orderBy('id_producto')
                ->get();
            
            if ($detalles->isEmpty()) {
                throw ValidationException::withMessages([
                    'pedido' => 'El pedido no contiene productos.'
                ]);
            }
            
            $total = 0;
            
            foreach ($detalles as $detalle) {
                
                $producto = Producto::whereKey($detalle->id_producto)
                    ->lockForUpdate()
                    ->firstOrFail();
                
                if ($producto->stock < $detalle->cantidad) {
                    throw ValidationException::withMessages([
                        'pedido' => "Stock insuficiente para {$producto->nombre}.",
                    ]);
                }
                
                $producto->decrement('stock', $detalle->cantidad);
                
                $total += $detalle->cantidad * $detalle->precio_unitario;
            }
            
            $pedido->update([
                'estado' => 'Confirmado',
            ]);
            
            MovimientoFinanciero::create([
                'tipo' => 'Ingreso',
                'concepto' => "Venta del pedido #{$pedido->id}",
                'monto' => round($total, 2),
                'id_pedido' => $pedido->id,
            ]);
    });
    
    return redirect()
        ->route('pedidos.index')
        ->with('success', "Pedido #{$id} confirmado correctamente.");
    }
    
    public function entregar(int $id)
    {
        DB::transaction(function () use ($id) {
            
            $pedido = Pedido::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();
            
            if ($pedido->estado !== 'Confirmado') {
                throw ValidationException::withMessages([
                    'pedido' => 'Solo se pueden entregar pedidos confirmados.',
                ]);
            }
            
            $pedido->update([
                'estado' => 'Entregado',
            ]);
        });
        
        return redirect()
            ->route('pedidos.index')
            ->with('success', "Pedido #{$id} entregado correctamente.");
    }
    
    public function cancelar(int $id)
    {
        DB::transaction(function () use ($id) {
            
            $pedido = Pedido::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();
            
            if ($pedido->estado !== 'Pendiente') {
                throw ValidationException::withMessages([
                    'pedido' => 'Solo se pueden cancelar pedidos pendientes.',
                ]);
            }
            
            $pedido->update([
                'estado' => 'Cancelado',
            ]);
        });
        
        return redirect()
            ->route('pedidos.index')
            ->with('success', "Pedido #{$id} cancelado correctamente.");
    }
}