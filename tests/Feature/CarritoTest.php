<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\LuminaPedidosTestCase;

class CarritoTest extends LuminaPedidosTestCase
{
    public function test_guarda_actualiza_y_elimina_una_seleccion_en_la_sesion(): void
    {
        $producto = $this->crearProducto('Anillo', 5, '12.25');

        $this->get(route('carrito.index'))->assertOk()->assertSeeText('Tu carrito está vacío');

        $this->post(route('carrito.store'), [
            'id_producto' => $producto->id,
            'cantidad' => 1,
            'precio' => '0.01',
        ])->assertRedirect(route('carrito.index'))
            ->assertSessionHas('carrito.'.$producto->id, 1);

        $this->post(route('carrito.store'), [
            'id_producto' => $producto->id,
            'cantidad' => 2,
        ])->assertRedirect(route('carrito.index'))
            ->assertSessionHas('carrito.'.$producto->id, 3);

        $this->get('/')
            ->assertOk()
            ->assertSee('href="'.route('carrito.index').'"', false);
        $this->get(route('carrito.index'))->assertSeeText('Anillo')->assertSeeText('$36.75');

        $this->patch(route('carrito.update', $producto->id), ['cantidad' => 4])
            ->assertRedirect(route('carrito.index'))
            ->assertSessionHas('carrito.'.$producto->id, 4);

        $this->delete(route('carrito.destroy', $producto->id))
            ->assertRedirect(route('carrito.index'))
            ->assertSessionMissing('carrito.'.$producto->id);

        $this->get(route('carrito.index'))->assertSeeText('Tu carrito está vacío');
        $this->assertSame(5, (int) $producto->fresh()->stock);
    }

    public function test_rechaza_cantidades_invalidas_y_productos_inexistentes(): void
    {
        $producto = $this->crearProducto('Anillo', 3, '12.25');

        $this->post(route('carrito.store'), ['id_producto' => 999, 'cantidad' => 1])
            ->assertRedirect(route('carrito.index'))
            ->assertSessionHasErrors('id_producto');

        foreach ([0, -1, 1.5, 4] as $cantidad) {
            $this->post(route('carrito.store'), ['id_producto' => $producto->id, 'cantidad' => $cantidad])
                ->assertRedirect(route('carrito.index'))
                ->assertSessionHasErrors('cantidad')
                ->assertSessionMissing('carrito.'.$producto->id);
        }

        $this->withSession(['carrito' => [$producto->id => 2]])
            ->post(route('carrito.store'), ['id_producto' => $producto->id, 'cantidad' => 2])
            ->assertSessionHasErrors('cantidad')
            ->assertSessionHas('carrito.'.$producto->id, 2);

        $this->patch(route('carrito.update', $producto->id), ['cantidad' => 4])
            ->assertSessionHasErrors('cantidad')
            ->assertSessionHas('carrito.'.$producto->id, 2);
    }

    public function test_la_vista_de_producto_entrega_el_formulario_compatible_con_el_carrito(): void
    {
        $producto = $this->crearProducto('Collar de prueba', 3, '22.50');

        Route::middleware('web')->get('/pruebas/detalle', fn () => view('store.product', [
            'producto' => $producto,
            'cartAction' => route('carrito.store'),
        ]));

        $this->get('/pruebas/detalle')
            ->assertOk()
            ->assertSeeText('Agregar al carrito')
            ->assertSee('action="'.route('carrito.store').'" method="POST"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="id_producto" value="'.$producto->id.'"', false)
            ->assertSee('name="cantidad"', false);
    }

    public function test_avisa_cuando_el_stock_cambia_o_el_producto_desaparece(): void
    {
        $producto = $this->crearProducto('Anillo', 1, '12.25');

        $this->withSession(['carrito' => [$producto->id => 2]])
            ->get(route('carrito.index'))
            ->assertOk()
            ->assertSeeText('Ajusta la cantidad según el stock disponible.');

        $producto->delete();

        $this->get(route('carrito.index'))
            ->assertOk()
            ->assertSeeText('Producto no disponible')
            ->assertSeeText('Quitar');
    }
}
