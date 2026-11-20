<?php

namespace App\Http\Controllers;

use App\Services\Carrito;
use App\Services\Checkout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request, Carrito $carrito): View|RedirectResponse
    {
        $items = $carrito->items();

        if ($items->isEmpty() || !$items->every('disponible')) {
            return redirect()->route('carrito.index')
                ->with('error', 'Revisa el carrito antes de finalizar el pedido.');
        }

        $sesion = $request->session();
        $precios = $items->mapWithKeys(fn (array $item) => [$item['id'] => (string) $item['producto']->precio])->all();
        $token = $sesion->get('checkout.token');

        if (!$token || $sesion->get('checkout.precios') !== $precios) {
            $token = (string) Str::uuid();
            $sesion->put(['checkout.token' => $token, 'checkout.precios' => $precios]);
        }

        return view('store.checkout', [
            'items' => $items,
            'total' => $items->sum('subtotal'),
            'token' => $token,
        ]);
    }

    public function store(Request $request, Carrito $carrito, Checkout $checkout): RedirectResponse
    {
        $sesion = $request->session();
        $token = $request->input('token');

        if (is_string($token) && $token === $sesion->get('checkout.confirmado.token')) {
            return redirect()->route('checkout.confirmacion');
        }

        if (!is_string($token) || $token !== $sesion->get('checkout.token')) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito cambió o la sesión venció. Revisa tu selección y vuelve a intentar.');
        }

        try {
            $datos = $request->validate([
                'nombre' => ['required', 'string', 'max:100'],
                'correo' => ['required', 'email', 'max:100'],
                'telefono' => ['nullable', 'string', 'max:20'],
            ], [
                'nombre.required' => 'Escribe tu nombre.',
                'nombre.string' => 'El nombre debe ser texto.',
                'nombre.max' => 'El nombre admite hasta :max caracteres.',
                'correo.required' => 'Escribe tu correo electrónico.',
                'correo.email' => 'Escribe un correo electrónico válido.',
                'correo.max' => 'El correo admite hasta :max caracteres.',
                'telefono.string' => 'El teléfono debe ser texto.',
                'telefono.max' => 'El teléfono admite hasta :max caracteres.',
            ]);

            $datos['correo'] = Str::lower($datos['correo']);
            $pedidoId = $checkout->registrar(
                $token,
                $carrito->cantidades(),
                $sesion->get('checkout.precios', []),
                $datos
            );
        } catch (ValidationException $error) {
            $ruta = array_key_exists('carrito', $error->errors()) ? 'carrito.index' : 'checkout.index';
            throw $error->redirectTo(route($ruta));
        }

        $carrito->vaciar();
        $sesion->forget('checkout.precios');
        $sesion->put('checkout.confirmado', ['token' => $token, 'id' => $pedidoId]);

        return redirect()->route('checkout.confirmacion');
    }

    public function confirmacion(Request $request): View|RedirectResponse
    {
        $pedidoId = $request->session()->get('checkout.confirmado.id');

        if (!$pedidoId) {
            return redirect()->route('carrito.index');
        }

        return view('store.confirmacion', ['pedidoId' => $pedidoId]);
    }
}
