<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Adeudo;
use App\Models\Programa;

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
        $promedioPago = count($pagos) > 0 ? $suma / count($pagos) : 0;

        $programasTop = Programa::withCount('inscripciones')
            ->orderBy('inscripciones_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'alumnosActivos', 'totalAlumnos', 'pagosMes',
            'totalInscripciones', 'promedioPago', 'programasTop'
        ));
    }
}
