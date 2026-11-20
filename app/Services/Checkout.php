<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Checkout
{
    public function registrar(string $token, array $cantidades, array $precios, array $datos): int
    {
        return DB::transaction(function () use ($token, $cantidades, $precios, $datos): int {
            DB::table('checkout_intentos')->insertOrIgnore(['token' => $token]);
            $intento = DB::table('checkout_intentos')->where('token', $token);
            $pedidoRegistrado = $intento->lockForUpdate()->value('id_pedido');

            if ($pedidoRegistrado !== null) {
                return (int) $pedidoRegistrado;
            }

            if ($cantidades === []) {
                throw ValidationException::withMessages(['carrito' => 'El carrito está vacío.']);
            }

            $productos = Producto::whereIn('id', array_keys($cantidades))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $detalles = [];
            $totalCentavos = 0;

            foreach ($cantidades as $id => $cantidad) {
                $producto = $productos->get($id);

                if (!$producto || $cantidad < 1 || $producto->stock < $cantidad) {
                    $nombre = $producto?->nombre ?? 'un producto del carrito';
                    throw ValidationException::withMessages(['carrito' => "Revisa la disponibilidad de {$nombre}."]);
                }

                if (($precios[$id] ?? null) !== (string) $producto->precio) {
                    throw ValidationException::withMessages([
                        'precios' => 'Los precios cambiaron. Revisa el total antes de registrar el pedido.',
                    ]);
                }

                $totalCentavos += (int) round($producto->precio * 100) * $cantidad;

                if ($totalCentavos > 9999999999) {
                    throw ValidationException::withMessages(['carrito' => 'Reduce la cantidad de productos para completar el pedido.']);
                }

                $detalles[] = [
                    'id_producto' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio,
                ];
            }

            $cliente = Cliente::firstOrCreate(
                ['correo' => $datos['correo']],
                ['nombre' => $datos['nombre'], 'telefono' => $datos['telefono'] ?? null]
            );

            $pedido = Pedido::create([
                'id_cliente' => $cliente->id,
                'estado' => 'Pendiente',
            ]);

            $pedido->detalles()->createMany($detalles);
            $intento->update(['id_pedido' => $pedido->id]);

            return $pedido->id;
        }, 3);
    }
}
