<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $datos = $request->validate([
            'correo' => ['required', 'email'],
            'contrasena' => ['required', 'string'],
        ]);
        
        $clave = Str::lower($datos['correo']) . '|' . $request->ip();
        $claveBloqueo = 'bloqueo-login:' . $clave;
        
        if (RateLimiter::tooManyAttempts($claveBloqueo, 1)) {
            $segundos = RateLimiter::availableIn($claveBloqueo);
            
            return back()->withErrors([
                'correo' => "Demasiados intentos fallidos. Espera {$segundos} segundos.",
            ])->onlyInput('correo');
        }
        
        if (Auth::attempt([
            'correo' => $datos['correo'],
            'password' => $datos['contrasena'],
        ])) {
            RateLimiter::clear($clave);
            
            $request->session()->regenerate();
            
            return redirect('/panel');
        }
        
        RateLimiter::hit($clave, 60);
        if (RateLimiter::tooManyAttempts($clave, 5)) {
            RateLimiter::hit($claveBloqueo, 15);
            RateLimiter::clear($clave);
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
}