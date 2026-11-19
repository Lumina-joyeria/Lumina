<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Services\Checkout;
use Tests\LuminaPedidosTestCase;

class CheckoutTest extends LuminaPedidosTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $migracion = require database_path('migrations/2026_11_18_000001_create_checkout_intentos_table.php');
        $migracion->up();
    }

    public function test_registra_un_pedido_pendiente_y_respeta_el_doble_envio(): void
    {
        $producto = $this->crearProducto('Anillo', 5, '12.25');
        $token = $this->abrirCheckout($producto->id, 2);
        $datos = $this->contacto($token);

        $this->post(route('checkout.store'), $datos + ['precio' => '0.01'])
            ->assertRedirect(route('checkout.confirmacion'))
            ->assertSessionMissing('carrito');

        $pedido = Pedido::firstOrFail();
        $this->assertSame('Pendiente', $pedido->estado);
        $this->assertDatabaseHas('detalle_pedidos', [
            'id_pedido' => $pedido->id,
            'id_producto' => $producto->id,
            'cantidad' => 2,
            'precio_unitario' => '12.25',
        ]);
        $this->assertDatabaseHas('clientes', ['correo' => 'comprador@example.com', 'telefono' => null]);
        $this->assertSame(5, (int) $producto->fresh()->stock);
        $this->assertDatabaseCount('movimientos_financieros', 0);

        $this->post(route('checkout.store'), $datos)->assertRedirect(route('checkout.confirmacion'));
        $this->get(route('checkout.confirmacion'))->assertOk()->assertSeeText("Pedido #{$pedido->id} registrado");

        $this->assertDatabaseCount('pedidos', 1);
        $this->assertDatabaseCount('detalle_pedidos', 1);
        $this->assertDatabaseCount('checkout_intentos', 1);
        $this->assertGuest();
    }

    public function test_reconoce_un_pedido_persistido_aunque_la_sesion_aun_conserve_el_carrito(): void
    {
        $producto = $this->crearProducto('Collar', 3, '20.50');
        $token = $this->abrirCheckout($producto->id, 1);
        $datos = $this->contacto($token);
        $pedidoId = app(Checkout::class)->registrar(
            $token,
            [$producto->id => 1],
            [$producto->id => (string) $producto->fresh()->precio],
            $datos
        );

        $producto->update(['stock' => 0]);

        $this->post(route('checkout.store'), $datos)
            ->assertRedirect(route('checkout.confirmacion'))
            ->assertSessionHas('checkout.confirmado.id', $pedidoId)
            ->assertSessionMissing('carrito');

        $this->assertDatabaseCount('pedidos', 1);
    }

    public function test_revalida_stock_y_conserva_el_carrito_si_el_pedido_falla(): void
    {
        $producto = $this->crearProducto('Collar', 3, '20.50');
        $token = $this->abrirCheckout($producto->id, 2);

        $producto->update(['stock' => 1]);

        $this->post(route('checkout.store'), $this->contacto($token))
            ->assertRedirect(route('carrito.index'))
            ->assertSessionHasErrors('carrito')
            ->assertSessionHas('carrito.'.$producto->id, 2);

        $this->assertDatabaseCount('pedidos', 0);
        $this->assertDatabaseCount('checkout_intentos', 0);
        $this->assertDatabaseCount('clientes', 0);
    }

    public function test_pide_revisar_el_precio_nuevo_antes_de_registrar_el_pedido(): void
    {
        $producto = $this->crearProducto('Collar', 3, '20.50');
        $token = $this->abrirCheckout($producto->id, 2);

        $producto->update(['precio' => '22.75']);

        $this->followingRedirects()
            ->post(route('checkout.store'), $this->contacto($token))
            ->assertOk()
            ->assertSeeText('Los precios cambiaron.')
            ->assertSeeText('$45.50');

        $this->assertDatabaseCount('pedidos', 0);
        $tokenNuevo = session('checkout.token');
        $this->assertNotSame($token, $tokenNuevo);

        $this->post(route('checkout.store'), $this->contacto($tokenNuevo))
            ->assertRedirect(route('checkout.confirmacion'));

        $this->assertDatabaseHas('detalle_pedidos', ['precio_unitario' => '22.75']);
    }

    public function test_valida_los_datos_reutiliza_el_cliente_y_conserva_sus_datos(): void
    {
        $cliente = Cliente::create([
            'nombre' => 'Nombre en el directorio',
            'correo' => 'comprador@example.com',
            'telefono' => '099 123 4567',
        ]);
        $producto = $this->crearProducto('Collar', 3, '20.50');
        $token = $this->abrirCheckout($producto->id, 1);

        $this->post(route('checkout.store'), ['token' => $token, 'correo' => 'correo-invalido'])
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHasErrors(['nombre', 'correo'])
            ->assertSessionHas('carrito.'.$producto->id, 1);

        $this->post(route('checkout.store'), $this->contacto($token))
            ->assertRedirect(route('checkout.confirmacion'));

        $this->assertDatabaseCount('clientes', 1);
        $this->assertDatabaseHas('pedidos', ['id_cliente' => $cliente->id]);
        $this->assertSame('Nombre en el directorio', $cliente->fresh()->nombre);
        $this->assertSame('099 123 4567', $cliente->fresh()->telefono);
    }

    public function test_no_abre_checkout_con_carrito_vacio_ni_acepta_un_token_ajeno(): void
    {
        $this->get(route('checkout.index'))->assertRedirect(route('carrito.index'));
        $this->get(route('checkout.confirmacion'))->assertRedirect(route('carrito.index'));
        $this->post(route('checkout.store'), $this->contacto('invalido'))
            ->assertRedirect(route('carrito.index'));

        $this->assertDatabaseCount('pedidos', 0);
    }

    private function abrirCheckout(int $producto, int $cantidad): string
    {
        $this->post(route('carrito.store'), ['id_producto' => $producto, 'cantidad' => $cantidad])
            ->assertRedirect(route('carrito.index'));

        $this->get(route('checkout.index'))->assertOk()->assertSeeText('Resumen del pedido');

        return session('checkout.token');
    }

    private function contacto(string $token): array
    {
        return [
            'token' => $token,
            'nombre' => 'Comprador de prueba',
            'correo' => 'comprador@example.com',
        ];
    }
}
