<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class NivelMiddleware
{
    public function handle(Request $request, Closure $next, ...$niveles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        $cacheKey = "user_nivel_id_{$user->id}";
        // 10 minutos de cache
        $userNivel = Cache::remember($cacheKey, 600, function () use ($user) {
            return $user->nivel_id;
        });

        if ($userNivel === null) {
            return redirect()->route('login')
                ->with('error', 'Usuario sin nivel asignado');
        }

        if (empty($niveles)) {
            return $next($request);
        }

        $nivelesPermitidos = array_map('intval', $niveles);

        if (in_array((int) $userNivel, $nivelesPermitidos)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'No tienes permisos para acceder a este recurso.',
                'required_levels' => $nivelesPermitidos,
                'user_level' => (int) $userNivel
            ], 403);
        }

        return redirect()->route('dashboard')
            ->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
