<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Adeudo;
use App\Models\ReporteDeuda;
use Illuminate\Support\Facades\Log;

class GenerarReporteDeudasJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $reporteId
    ) {}

    public function handle(): void
    {
        try {
            $reporte = ReporteDeuda::findOrFail($this->reporteId);
            $reporte->estado = 'pendiente';
            $reporte->save();

            $resultado = [];
            $errores = [];

            Adeudo::with('alumno')->chunk(200, function ($adeudos) use (&$resultado, &$errores) {
                foreach ($adeudos as $adeudo) {
                    try {
                        if (!$adeudo->alumno) {
                            $errores[] = "Adeudo {$adeudo->id}: Alumno no encontrado";
                            continue;
                        }

                        $resultado[] = [
                            'alumno' => $adeudo->alumno->nombre_completo ?? 'N/A',
                            'monto' => $adeudo->monto ?? 0,
                        ];
                    } catch (\Exception $e) {
                        $errores[] = "Adeudo {$adeudo->id}: " . $e->getMessage();
                    }
                }
            });

            $reporte->resultado = json_encode([
                'datos' => $resultado,
                'errores' => $errores,
                'total' => count($resultado),
                'errores_total' => count($errores),
            ]);
            $reporte->estado = count($errores) > 0 ? 'rechazado' : 'aprobado';
            $reporte->save();

            Log::info('Reporte generado', [
                'reporte_id' => $reporte->id,
                'total' => count($resultado),
                'errores' => count($errores)
            ]);

        } catch (\Exception $e) {
            Log::error('Error en reporte', [
                'reporte_id' => $this->reporteId,
                'error' => $e->getMessage()
            ]);

            if ($reporte = ReporteDeuda::find($this->reporteId)) {
                $reporte->estado = 'rechazado';
                $reporte->resultado = json_encode(['error' => $e->getMessage()]);
                $reporte->save();
            }

            throw $e;
        }
    }
}
