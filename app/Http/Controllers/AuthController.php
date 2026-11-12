<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        if (Auth::attempt([
            'correo' => $datos['correo'],
            'password' => $datos['contrasena'],
        ])) {
            $request->session()->regenerate();
            
            return redirect('/panel');
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