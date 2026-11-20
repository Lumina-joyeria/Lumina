<?php

namespace Tests\Feature;

use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Tests\LuminaFeatureTestCase;

class ClientesTest extends LuminaFeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->crearUsuario());
    }

    public function test_registra_y_edita_un_cliente_con_su_propio_correo(): void
    {
        $datos = [
            'nombre' => 'Ana de prueba',
            'correo' => 'ana@example.com',
            'telefono' => '099 123 4567',
        ];

        $this->post(route('clientes.store'), $datos)
            ->assertRedirect(route('clientes.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('clientes', $datos);
        $cliente = Cliente::where('correo', $datos['correo'])->firstOrFail();

        $datos['nombre'] = 'Ana actualizada';
        $datos['telefono'] = null;

        $this->patch(route('clientes.update', $cliente), $datos)
            ->assertRedirect(route('clientes.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('clientes', ['id' => $cliente->id] + $datos);
    }

    public function test_rechaza_el_correo_duplicado_y_conserva_los_datos_ingresados(): void
    {
        Cliente::create(['nombre' => 'Ana de prueba', 'correo' => 'ana@example.com']);
        $otroCliente = Cliente::create(['nombre' => 'Luis de prueba', 'correo' => 'luis@example.com']);
        $datos = [
            'nombre' => 'Nombre escrito',
            'correo' => 'ana@example.com',
            'telefono' => '099 123 4567',
        ];

        $this->from(route('clientes.create'))->post(route('clientes.store'), $datos)
            ->assertRedirect(route('clientes.create'))
            ->assertSessionHasErrors(['correo' => 'Ya existe un cliente con este correo.'])
            ->assertSessionHas('_old_input', $datos);

        $this->from(route('clientes.edit', $otroCliente))
            ->patch(route('clientes.update', $otroCliente), $datos)
            ->assertRedirect(route('clientes.edit', $otroCliente))
            ->assertSessionHasErrors(['correo' => 'Ya existe un cliente con este correo.'])
            ->assertSessionHas('_old_input', $datos);

        $this->assertDatabaseCount('clientes', 2);
        $this->assertDatabaseHas('clientes', ['id' => $otroCliente->id, 'correo' => 'luis@example.com']);
    }

    public function test_elimina_a_un_cliente_sin_pedidos(): void
    {
        $cliente = Cliente::create(['nombre' => 'Ana de prueba', 'correo' => 'ana@example.com']);

        $this->delete(route('clientes.destroy', $cliente))
            ->assertRedirect(route('clientes.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    public function test_conserva_al_cliente_si_tiene_pedidos(): void
    {
        $cliente = Cliente::create(['nombre' => 'Ana de prueba', 'correo' => 'ana@example.com']);
        DB::table('pedidos')->insert(['id_cliente' => $cliente->id]);

        $this->get(route('clientes.index'))->assertOk()->assertSeeText('Tiene pedidos');

        $this->delete(route('clientes.destroy', $cliente))
            ->assertRedirect(route('clientes.index'))
            ->assertSessionHas('error', 'No se puede eliminar este cliente porque tiene pedidos registrados.');

        $this->assertDatabaseHas('clientes', ['id' => $cliente->id]);
        $this->assertDatabaseHas('pedidos', ['id_cliente' => $cliente->id]);
    }
}
