<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Adeudo;
use App\Models\ReporteDeuda;

class GenerarReporteDeudasJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $reporteId
    ) {}

    public function handle(): void
    {
        $reporte = ReporteDeuda::find($this->reporteId);
        $reporte->estado = 'procesando';
        $reporte->save();

        $adeudos = Adeudo::with('alumno')->get();

        $resultado = [];
        foreach ($adeudos as $adeudo) {
            $resultado[] = [
                'alumno' => $adeudo->alumno->nombre_completo ?? 'N/A',
                'monto' => $adeudo->monto,
            ];
        }

        $reporte->resultado = json_encode($resultado);
        $reporte->estado = 'completado';
        $reporte->save();
    }
}
