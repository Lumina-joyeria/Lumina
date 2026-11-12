<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloAdministrador
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || (int) $request->user()->id_rol !== 1) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        return $next($request);
    }
}
