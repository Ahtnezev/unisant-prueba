<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Adeudo;
use App\Models\Programa;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $alumnosActivos = Alumno::activosCount();
        $totalAlumnos = Alumno::totalAlumnos();
        $pagosMes = Pago::activos()->delMesActual()->sum('monto');

        $inscripcionesPendientes = Inscripcion::activos()->get();
        $totalInscripciones = count($inscripcionesPendientes);

        $pagos = Pago::all('monto');
        $suma = 0;
        foreach ($pagos as $pago) {
            $suma += (float) $pago->monto;
        }

        // tambien se puede actualizar el cache cada que se cree o actualice un pago
        $promedioPago = Cache::remember('promedio_pago', 300, function () use ($pagos, $suma) {
            return count($pagos) > 0 ? $suma / count($pagos) : 0;
        });

        $programasTop = Cache::remember('programas_top', 300, function () {
            return Programa::withCount('inscripciones')
                ->where('nombre', '!=', '')
                ->having('inscripciones_count', '>', 0)
                ->orderBy('inscripciones_count', 'desc')
                ->take(5)
                ->get();
        });

        return view('dashboard.index', compact(
            'alumnosActivos', 'totalAlumnos', 'pagosMes',
            'totalInscripciones', 'promedioPago', 'programasTop'
        ));
    }
}
