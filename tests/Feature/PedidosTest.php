<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Pedido;
use Tests\LuminaPedidosTestCase;

class PedidosTest extends LuminaPedidosTestCase
{
    private Cliente $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->crearUsuario());
        $this->cliente = Cliente::create(['nombre' => 'Cliente de prueba', 'correo' => 'cliente@example.com']);
    }

    public function test_registra_pedidos_con_uno_y_varios_productos_sin_descontar_stock(): void
    {
        $anillo = $this->crearProducto('Anillo de prueba', 10, '15.25');
        $collar = $this->crearProducto('Collar de prueba', 5, '24.50');
        $item = ['id_producto' => $anillo->id, 'cantidad' => 2, 'precio_unitario' => '0.01'];

        $this->post(route('pedidos.store'), [
            'id_cliente' => $this->cliente->id,
            'items' => [$item],
        ])->assertRedirect(route('pedidos.create'))->assertSessionHas('success');

        $pedido = $this->crearPedido([
            $item,
            ['id_producto' => $collar->id, 'cantidad' => 1],
        ]);

        $this->assertDatabaseCount('pedidos', 2);
        $this->assertDatabaseCount('detalle_pedidos', 3);
        $this->assertDatabaseHas('detalle_pedidos', [
            'id_pedido' => $pedido->id,
            'id_producto' => $anillo->id,
            'cantidad' => 2,
            'precio_unitario' => '15.25',
        ]);
        $this->assertSame(10, (int) $anillo->fresh()->stock);
        $this->assertSame(5, (int) $collar->fresh()->stock);
        $this->assertDatabaseCount('movimientos_financieros', 0);

        $this->get(route('pedidos.index'))
            ->assertOk()
            ->assertSeeText('Pendiente')
            ->assertSeeText('Anillo de prueba')
            ->assertSeeText('Collar de prueba')
            ->assertSeeText('Total: $55.00');
    }

    public function test_confirma_y_entrega_sin_descontar_stock_ni_registrar_ingreso_dos_veces(): void
    {
        $producto = $this->crearProducto('Anillo de prueba', 5, '12.35');
        $pedido = $this->crearPedido([['id_producto' => $producto->id, 'cantidad' => 2]]);

        $this->patch(route('pedidos.confirmar', $pedido->id))
            ->assertRedirect(route('pedidos.index'))
            ->assertSessionHas('success');
        $this->assertSame('Confirmado', $pedido->fresh()->estado);
        $this->assertSame(3, (int) $producto->fresh()->stock);
        $this->assertDatabaseHas('movimientos_financieros', [
            'id_pedido' => $pedido->id,
            'tipo' => 'Ingreso',
            'monto' => '24.70',
        ]);

        $this->from(route('pedidos.index'))
            ->patch(route('pedidos.confirmar', $pedido->id))
            ->assertSessionHasErrors(['pedido' => 'Solo se pueden confirmar pedidos pendientes.']);

        $this->patch(route('pedidos.entregar', $pedido->id))
            ->assertRedirect(route('pedidos.index'))
            ->assertSessionHas('success');
        $this->assertSame('Entregado', $pedido->fresh()->estado);
        $this->assertSame(3, (int) $producto->fresh()->stock);
        $this->assertDatabaseCount('movimientos_financieros', 1);
    }

    public function test_revierte_la_confirmacion_si_un_producto_se_queda_sin_stock(): void
    {
        $anillo = $this->crearProducto('Anillo', 5, '12.00');
        $collar = $this->crearProducto('Collar', 3, '20.00');
        $pedido = $this->crearPedido([
            ['id_producto' => $anillo->id, 'cantidad' => 2],
            ['id_producto' => $collar->id, 'cantidad' => 2],
        ]);

        $collar->update(['stock' => 1]);

        $this->from(route('pedidos.index'))
            ->patch(route('pedidos.confirmar', $pedido->id))
            ->assertRedirect(route('pedidos.index'))
            ->assertSessionHasErrors(['pedido' => 'Stock insuficiente para Collar.']);

        $this->assertSame('Pendiente', $pedido->fresh()->estado);
        $this->assertSame(5, (int) $anillo->fresh()->stock);
        $this->assertSame(1, (int) $collar->fresh()->stock);
        $this->assertDatabaseCount('movimientos_financieros', 0);
    }

    public function test_cancela_un_pedido_pendiente_sin_afectar_stock(): void
    {
        $producto = $this->crearProducto('Anillo de prueba', 5, '15.00');
        $pedido = $this->crearPedido([['id_producto' => $producto->id, 'cantidad' => 2]]);

        $this->patch(route('pedidos.cancelar', $pedido->id))
            ->assertRedirect(route('pedidos.index'))
            ->assertSessionHas('success');

        $this->assertSame('Cancelado', $pedido->fresh()->estado);
        $this->assertSame(5, (int) $producto->fresh()->stock);
        $this->assertDatabaseCount('movimientos_financieros', 0);
    }

    public function test_el_formulario_recupera_el_cliente_y_las_filas_cuando_falla_la_validacion(): void
    {
        $producto = $this->crearProducto('Anillo de prueba', 5, '15.00');

        $this->followingRedirects()->from(route('pedidos.create'))->post(route('pedidos.store'), [
            'id_cliente' => $this->cliente->id,
            'items' => [3 => ['id_producto' => $producto->id, 'cantidad' => 0]],
        ])->assertOk()
            ->assertSee('name="items[3][cantidad]"', false)
            ->assertSee('id="cantidad-error-3"', false);

        $this->assertDatabaseCount('pedidos', 0);
    }

    private function crearPedido(array $items): Pedido
    {
        $this->post(route('pedidos.store'), [
            'id_cliente' => $this->cliente->id,
            'items' => $items,
        ])->assertRedirect(route('pedidos.create'))->assertSessionHasNoErrors();

        return Pedido::latest('id')->firstOrFail();
    }
}
