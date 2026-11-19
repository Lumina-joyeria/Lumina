<?php

namespace Tests\Feature;

use Tests\LuminaFeatureTestCase;

class AutenticacionTest extends LuminaFeatureTestCase
{
    public function test_inicia_y_cierra_sesion(): void
    {
        $usuario = $this->crearUsuario();

        $this->get('/panel')->assertRedirect(route('login'));
        $this->post(route('login.submit'), [
            'correo' => $usuario->correo,
            'contrasena' => self::CLAVE_PRUEBA,
        ])->assertRedirect('/panel');

        $this->assertAuthenticatedAs($usuario);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get('/panel')->assertRedirect(route('login'));
    }

    public function test_una_validacion_fallida_conserva_el_correo_sin_guardar_la_contrasena(): void
    {
        $this->from(route('login'))->post(route('login.submit'), [
            'correo' => 'correo-invalido',
            'contrasena' => self::CLAVE_PRUEBA,
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('correo')
            ->assertSessionHas('_old_input.correo', 'correo-invalido')
            ->assertSessionMissing('_old_input.contrasena');
    }

    public function test_bloquea_en_el_quinto_intento_y_permite_el_acceso_al_vencer(): void
    {
        $usuario = $this->crearUsuario();
        $this->freezeTime();

        $intentoIncorrecto = [
            'correo' => $usuario->correo,
            'contrasena' => 'incorrecta',
        ];

        for ($intento = 1; $intento <= 4; $intento++) {
            $this->from(route('login'))->post(route('login.submit'), $intentoIncorrecto)
                ->assertSessionHasErrors(['correo' => 'Correo o contraseña incorrectos.'])
                ->assertSessionMissing('login_lock_email');
        }

        $this->from(route('login'))->post(route('login.submit'), $intentoIncorrecto)
            ->assertRedirect(route('login'))
            ->assertSessionHas('login_lock_email', $usuario->correo);

        $this->get(route('login'))
            ->assertViewHas('loginLockSeconds', 15)
            ->assertSee('role="alert" aria-live="polite" data-login-lock-title', false)
            ->assertSee('role="timer" aria-live="off" data-login-lock-message', false)
            ->assertSeeText('Acceso temporalmente bloqueado');

        $this->travel(3)->seconds();
        $this->get(route('login'))->assertViewHas('loginLockSeconds', 12);

        $intentoCorrecto = [
            'correo' => $usuario->correo,
            'contrasena' => self::CLAVE_PRUEBA,
        ];

        $this->from(route('login'))->post(route('login.submit'), $intentoCorrecto)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        $this->travel(13)->seconds();
        $this->get(route('login'))->assertViewHas('loginLockSeconds', 0);
        $this->post(route('login.submit'), $intentoCorrecto)->assertRedirect('/panel');
        $this->assertAuthenticatedAs($usuario);
    }
}
