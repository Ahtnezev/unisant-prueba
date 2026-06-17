<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NivelMiddleware
{
    public function handle(Request $request, Closure $next, string $nivel): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        if ($user->nivel_id === null) {
            return redirect()->route('login')->with('error', 'Usuario sin nivel asignado');
        }

        if ((int) $user->nivel_id === (int) $nivel) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Acceso no autorizado');
    }
}
