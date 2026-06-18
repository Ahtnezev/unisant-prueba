<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Pago;
use App\Models\Adeudo;
use Illuminate\Http\Request;
use App\Jobs\GenerarReporteDeudasJob;

class ReporteController extends Controller
{
    public function exportarDeudas(Request $request)
    {
        $deudas = Adeudo::with('alumno')->get();

        $csv = "Alumno,Monto,Plazos\n";
        foreach ($deudas as $deuda) {
            $csv .= $deuda->alumno->nombre_completo . "," . $deuda->monto . "," . $deuda->plazos . "\n";
        }

        return response($csv)->header('Content-Type', 'text/csv');
    }

    public function generarReporteAsync(Request $request)
    {
        $reporte = \App\Models\ReporteDeuda::create([
            'nombre' => $request->input('nombre', 'Reporte sin nombre'),
            'filtros' => $request->input('filtros'),
            'estado' => 'pendiente',
        ]);

        GenerarReporteDeudasJob::dispatch($reporte->id);

        return response()->json(['message' => 'Reporte en proceso', 'id' => $reporte->id]);
    }

    public function buscarAlumnosGlobal(Request $request)
    {
        $q = $request->input('q', '');

        if (empty($q)) {
            return response()->json([]);
        }

        $alumnos = Alumno::where('nombre_completo', 'LIKE', "%{$q}%")
            ->orWhere('matricula', 'LIKE', "%{$q}%")
            ->orWhere('curp', 'LIKE', "%{$q}%")
            ->limit(50)
            ->get(['id', 'nombre_completo', 'matricula', 'curp']);

        return response()->json($alumnos);
    }
}
