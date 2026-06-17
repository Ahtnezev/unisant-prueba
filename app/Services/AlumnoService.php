<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoService
{
    public function getAlumnosFiltrados(Request $request): array
    {
        try {
            $query = Alumno::query()
                ->where('estado', 'activo')
                ->whereNotNull('nombre_completo')
                ->where('nombre_completo', '!=', '');

            $this->aplicarFiltros($query, $request);

            $alumnos = $query->get()->unique('curp')->values();

            $alumnosPaginados = $this->paginacionManual($alumnos, $request);

            $sedes = $this->getSedesActivas();

            return [
                'alumnos' => $alumnosPaginados,
                'sedes' => $sedes,
            ];

        } catch (\Exception $e) {
            Log::error('Error al obtener alumnos filtrados', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'alumnos' => new LengthAwarePaginator([], 0, 5, 1),
                'sedes' => collect(),
            ];
        }
    }

    private function aplicarFiltros($query, Request $request): void
    {
        if ($request->filled('buscar')) {
            $buscar = trim($request->input('buscar'));
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_completo', 'LIKE', "%{$buscar}%")
                    ->orWhere('matricula', 'LIKE', "%{$buscar}%");
            });
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->input('sede_id'));
        }
    }

    private function paginacionManual(Collection $alumnos, Request $request): LengthAwarePaginator
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 5);

        return new LengthAwarePaginator(
            $alumnos->forPage($page, $perPage)->values(),
            $alumnos->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
    }

    public function getSedesActivas(): Collection
    {
        try {
            return Cache::remember('sedes_activas_lista', 3600, function () {
                return Sede::activas()
                    ->orderBy('nombre', 'asc')
                    ->get(['id', 'nombre']);
            });
        } catch (\Exception $e) {
            Log::error('Error al obtener sedes activas', [
                'error' => $e->getMessage()
            ]);
            return collect();
        }
    }

    public function destroy(int $id): array
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return [
                'success' => false,
                'motivo' => 'Alumno no encontrado'
            ];
        }

        $pagosActivos = $alumno->pagos()->where('estado', 'activo')->count();
        if ($pagosActivos > 0) {
            return [
                'success' => false,
                'motivo' => "Tiene {$pagosActivos} pago(s) pendiente(s)"
            ];
        }

        $inscripcionesActivas = $alumno->inscripciones()->where('estado', 'activo')->count();
        if ($inscripcionesActivas > 0) {
            return [
                'success' => false,
                'motivo' => "Tiene {$inscripcionesActivas} inscripción(es) activa(s)"
            ];
        }

        return [
            'success' => true,
            'motivo' => null
        ];
    }

        public function crearAlumno(array $data): array
    {
        try {
            DB::beginTransaction();

            Log::info('Creando nuevo alumno', ['matricula' => $data['matricula']]);

            $alumno = Alumno::create([
                'matricula' => $data['matricula'],
                'nombre_completo' => $data['nombre_completo'],
                'apat' => $data['apat'],
                'amat' => $data['amat'],
                'curp' => $data['curp'],
                'email' => $data['email'],
                'telefono' => $data['telefono'],
                'sede_id' => $data['sede_id'],
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                'estado' => 'activo',
            ]);

            Log::info('Alumno creado', ['alumno_id' => $alumno->id]);

            if (!empty($data['programa_id'])) {
                Inscripcion::create([
                    'alumno_id' => $alumno->id,
                    'programa_id' => $data['programa_id'],
                    'sede_id' => $data['sede_id'],
                    'estado' => 'activo',
                    'fecha_inscripcion' => now(),
                ]);

                Log::info('Inscripción creada', [
                    'alumno_id' => $alumno->id,
                    'programa_id' => $data['programa_id']
                ]);
            }

            if (!empty($data['monto_inicial']) && $data['monto_inicial'] > 0) {
                Pago::create([
                    'matricula' => $alumno->matricula,
                    'concepto' => 'Inscripción inicial',
                    'monto' => $data['monto_inicial'],
                    'fecha_pago' => now(),
                    'sede_id' => $data['sede_id'],
                    'estado' => 'completed',
                    'metodo' => $data['tipo_pago'] ?? 'efectivo',
                ]);

                Log::info('Pago inicial creado', [
                    'alumno_id' => $alumno->id,
                    'monto' => $data['monto_inicial']
                ]);
            }

            DB::commit();

            Log::info('Alumno creado exitosamente', ['alumno_id' => $alumno->id]);

            return [
                'success' => true,
                'message' => 'Alumno creado exitosamente',
                'data' => $alumno,
                'code' => 200
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear alumno', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => 'Error al crear el alumno: ' . $e->getMessage(),
                'code' => 500
            ];
        }
    }

    public function clearSedesCache(): void
    {
        Cache::forget('sedes_activas_lista');
    }
}
