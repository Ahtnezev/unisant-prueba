<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NivelMiddleware
{
    public function handle(Request $request, Closure $next, string $nivel): Response
    {
        $user = $request->user();

        if ($user->nivel_id == $nivel) {
            return $next($request);
        }

        if ($user->nivel_id == null) {
            return redirect('/login');
        }

        return redirect('/dashboard');
    }
}
