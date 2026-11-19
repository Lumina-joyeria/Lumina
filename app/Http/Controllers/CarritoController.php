<?php

namespace App\Http\Controllers;

use App\Services\Carrito;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CarritoController extends Controller
{
    public function index(Carrito $carrito): View
    {
        $items = $carrito->items();

        return view('store.cart', [
            'items' => $items,
            'total' => $items->sum('subtotal'),
            'disponible' => $items->isNotEmpty() && $items->every('disponible'),
        ]);
    }

    public function store(Request $request, Carrito $carrito): RedirectResponse
    {
        try {
            $datos = $request->validate([
                'id_producto' => ['required', 'integer', 'min:1', 'max:2147483647'],
                'cantidad' => ['required', 'integer', 'min:1', 'max:2147483647'],
            ], [
                'id_producto.*' => 'Selecciona un producto válido.',
                'cantidad.*' => 'Ingresa una cantidad entera mayor que cero.',
            ]);

            $carrito->agregar((int) $datos['id_producto'], (int) $datos['cantidad']);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('carrito.index'));
        }

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, int $producto, Carrito $carrito): RedirectResponse
    {
        try {
            $datos = $request->validate([
                'cantidad' => ['required', 'integer', 'min:1', 'max:2147483647'],
            ], ['cantidad.*' => 'Ingresa una cantidad entera mayor que cero.']);

            $carrito->actualizar($producto, (int) $datos['cantidad']);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('carrito.index'));
        }

        return redirect()->route('carrito.index')->with('success', 'Cantidad actualizada.');
    }

    public function destroy(int $producto, Carrito $carrito): RedirectResponse
    {
        $carrito->eliminar($producto);

        return redirect()->route('carrito.index')->with('success', 'Producto retirado del carrito.');
    }
}
