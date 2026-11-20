<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $segundosBloqueo = 0;
        $correoBloqueado = $request->session()->get('login_lock_email');

        if ($correoBloqueado) {
            $claveBloqueo = $this->claveBloqueo(
                $correoBloqueado,
                $request->ip()
            );

            if (RateLimiter::tooManyAttempts($claveBloqueo, 1)) {
                $segundosBloqueo = RateLimiter::availableIn($claveBloqueo);
            } else {
                $request->session()->forget('login_lock_email');
            }
        }

        return view('auth.login', [
            'loginLockSeconds' => $segundosBloqueo,
        ]);
    }

    public function login(Request $request)
    {
        $datos = $request->validate([
            'correo' => ['required', 'email'],
            'contrasena' => ['required', 'string'],
        ]);

        $correo = Str::lower($datos['correo']);
        $clave = $this->claveIntentos($correo, $request->ip());
        $claveBloqueo = $this->claveBloqueo($correo, $request->ip());

        if (RateLimiter::tooManyAttempts($claveBloqueo, 1)) {
            $segundos = RateLimiter::availableIn($claveBloqueo);

            return $this->respuestaBloqueo(
                $request,
                $correo,
                $segundos
            );
        }

        if (Auth::attempt([
            'correo' => $datos['correo'],
            'password' => $datos['contrasena'],
        ])) {
            RateLimiter::clear($clave);
            RateLimiter::clear($claveBloqueo);
            $request->session()->forget('login_lock_email');

            $request->session()->regenerate();

            return redirect('/panel');
        }

        RateLimiter::hit($clave, 60);

        if (RateLimiter::tooManyAttempts($clave, 5)) {
            RateLimiter::hit($claveBloqueo, 15);
            RateLimiter::clear($clave);

            $segundos = RateLimiter::availableIn($claveBloqueo);

            return $this->respuestaBloqueo(
                $request,
                $correo,
                $segundos
            );
        }

        return back()->withErrors([
            'correo' => 'Correo o contraseña incorrectos.',
        ])->onlyInput('correo');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function claveIntentos(string $correo, string $ip): string
    {
        return $correo . '|' . $ip;
    }

    private function claveBloqueo(string $correo, string $ip): string
    {
        return 'bloqueo-login:' . $this->claveIntentos($correo, $ip);
    }

    private function respuestaBloqueo(
        Request $request,
        string $correo,
        int $segundos
    ): RedirectResponse {
        $request->session()->put('login_lock_email', $correo);

        $unidad = $segundos === 1 ? 'segundo' : 'segundos';

        return back()
            ->withErrors([
                'correo' => "Demasiados intentos fallidos. Inténtalo nuevamente en {$segundos} {$unidad}.",
            ])
            ->onlyInput('correo');
    }
}
