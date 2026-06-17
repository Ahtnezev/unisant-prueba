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
        $alumnosActivos = Alumno::where('estado', 'activo')->count();
        $totalAlumnos = Alumno::all()->count();
        $pagosMes = Pago::where('estado', 'activo')
            ->where('fecha_pago', '>=', now()->startOfMonth())
            ->where('fecha_pago', '<=', now()->endOfMonth())
            ->sum('monto');

        $inscripcionesPendientes = Inscripcion::where('estado', 'activo')->get();
        $totalInscripciones = count($inscripcionesPendientes);

        $pagos = Pago::all();
        $suma = 0;
        foreach ($pagos as $pago) {
            $suma += (float) $pago->monto;
        }
        $promedioPago = count($pagos) > 0 ? $suma / count($pagos) : 0;

        $programasTop = Programa::all()->sortByDesc(function($p) {
            return $p->inscripciones->count();
        })->take(5);

        return view('dashboard.index', compact(
            'alumnosActivos', 'totalAlumnos', 'pagosMes',
            'totalInscripciones', 'promedioPago', 'programasTop'
        ));
    }
}
