<?php

namespace Tests\Feature;

use Tests\LuminaFeatureTestCase;

class PanelTest extends LuminaFeatureTestCase
{
    private const RUTAS_ADMIN = [
        'productos.index',
        'categorias.index',
        'clientes.index',
        'pedidos.index',
        'compras.create',
        'finanzas.index',
    ];

    public function test_el_panel_del_administrador_muestra_los_seis_modulos(): void
    {
        $usuario = $this->crearUsuario();

        $panel = $this->actingAs($usuario)->get('/panel')
            ->assertOk()
            ->assertSeeText('Hola, ' . $usuario->nombre);

        foreach (self::RUTAS_ADMIN as $ruta) {
            $panel->assertSee('href="' . route($ruta) . '"', false);
        }
    }

    public function test_el_empleado_no_ve_modulos_administrativos_y_el_servidor_restringe_sus_rutas(): void
    {
        $usuario = $this->crearUsuario(2);

        $panel = $this->actingAs($usuario)->get('/panel')
            ->assertOk()
            ->assertSeeText('Hola, ' . $usuario->nombre);

        foreach (self::RUTAS_ADMIN as $ruta) {
            $panel->assertDontSee('href="' . route($ruta) . '"', false);
            $this->get(route($ruta))->assertForbidden();
        }
    }

    public function test_el_pie_de_la_tienda_muestra_guayaquil(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeText('Guayaquil, Ecuador')
            ->assertDontSeeText('Quito, Ecuador');
    }
}
