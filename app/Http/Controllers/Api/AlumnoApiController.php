<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PagoResource;
use App\Models\Alumno;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AlumnoApiController extends Controller
{
    // se pueden dar mensajes falsos-positivos en el API para evitar
    // que atacantes, si les damos mensajes especificos les damos pistas
    // se pueden agregar algun resource para tener menos codigo y sea mas legible

    public function show($matricula)
    {
        $matricula = trim($matricula);

        try {
            if (!preg_match('/^[A-Z0-9]{8,15}$/', $matricula)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formato de matrícula inválido. Debe tener entre 8 y 15 caracteres alfanuméricos.'
                ], 422);
            }

            $alumno = Alumno::where('matricula', $matricula)
                ->where('estado', 'activo')
                ->first();

            if (!$alumno) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alumno no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $alumno
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function pagos(Request $request, $matricula)
    {
        $matricula = trim($matricula);

        try {
            if (!preg_match('/^[A-Z0-9]{8,15}$/', $matricula)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formato de matrícula inválido'
                ], 422);
            }

            $alumno = Alumno::where('matricula', $matricula)
                ->where('estado', 'activo')
                ->first();

            if (!$alumno) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alumno no encontrado'
                ], 404);
            }

            $perPage = $request->input('per_page', 15);
            $pagos = \App\Models\Pago::where('matricula', $matricula)
                ->where('estado', 'activo')
                ->orderBy('fecha_pago', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'alumno' => $alumno->only(['id', 'nombre_completo', 'email', 'matricula']),
                    'pagos' => $pagos->items(),
                    'pagination' => [
                        'current_page' => $pagos->currentPage(),
                        'per_page' => $pagos->perPage(),
                        'total' => $pagos->total(),
                        'last_page' => $pagos->lastPage(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los pagos'
            ], 500);
        }
    }

}
