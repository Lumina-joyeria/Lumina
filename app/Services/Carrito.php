<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class Carrito
{
    public function __construct(private Session $session) {}

    public function cantidades(): array
    {
        return $this->session->get('carrito', []);
    }

    public function items(): Collection
    {
        $cantidades = $this->cantidades();
        $productos = Producto::whereIn('id', array_keys($cantidades))->get()->keyBy('id');

        return collect($cantidades)->map(function (int $cantidad, int $id) use ($productos) {
            $producto = $productos->get($id);

            return [
                'id' => $id,
                'producto' => $producto,
                'cantidad' => $cantidad,
                'subtotal' => $producto ? $producto->precio * $cantidad : 0,
                'disponible' => $producto && $producto->stock >= $cantidad,
            ];
        });
    }

    public function agregar(int $id, int $cantidad): void
    {
        $cantidades = $this->cantidades();
        $this->guardarCantidad($cantidades, $id, ($cantidades[$id] ?? 0) + $cantidad);
    }

    public function actualizar(int $id, int $cantidad): void
    {
        $cantidades = $this->cantidades();

        if (!isset($cantidades[$id])) {
            throw ValidationException::withMessages(['cantidad' => 'Ese producto ya no está en el carrito.']);
        }

        $this->guardarCantidad($cantidades, $id, $cantidad);
    }

    public function eliminar(int $id): void
    {
        $cantidades = $this->cantidades();
        unset($cantidades[$id]);
        $this->guardar($cantidades);
    }

    public function vaciar(): void
    {
        $this->session->forget(['carrito', 'checkout.token']);
    }

    private function guardarCantidad(array $cantidades, int $id, int $cantidad): void
    {
        $producto = Producto::find($id);

        if (!$producto) {
            throw ValidationException::withMessages(['id_producto' => 'El producto ya no está disponible.']);
        }

        if ($cantidad > $producto->stock) {
            throw ValidationException::withMessages([
                'cantidad' => "Solo hay {$producto->stock} unidades disponibles de {$producto->nombre}.",
            ]);
        }

        $cantidades[$id] = $cantidad;
        $this->guardar($cantidades);
    }

    private function guardar(array $cantidades): void
    {
        $this->session->put('carrito', $cantidades);
        $this->session->forget('checkout.token');
    }
}
