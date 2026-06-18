<?php

namespace App\Console\Commands;

use App\Models\Adeudo;
use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RepararInconsistencias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reparar:inconsistencias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir aquellas inconsistencias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Iniciando limpieza auto...\n");

        $this->cleanAlumnos();
        $this->cleanPagos();
        $this->cleanAdeudos();
        $this->cleanInscripciones();

        $this->info("\nLimpieza auto completada");

        return Command::SUCCESS;
    }

    private function cleanAlumnos(): void
    {
        DB::transaction(function () {
            Alumno::whereNotNull('curp')->update([
                'curp' => DB::raw("TRIM(curp)")
            ]);

            Alumno::whereNotNull('email')->update([
                'email' => DB::raw("LOWER(TRIM(email))")
            ]);

            Alumno::whereNotNull('telefono')->update([
                'telefono' => DB::raw("REGEXP_REPLACE(telefono, '[^0-9]', '')")
            ]);

            Alumno::whereNotNull('nombre_completo')->update([
                'nombre_completo' => DB::raw("
                    CASE
                        WHEN TRIM(nombre_completo) = '' THEN 'SIN NOMBRE'
                        ELSE CONCAT(
                            UPPER(SUBSTRING(TRIM(nombre_completo), 1, 1)),
                            LOWER(SUBSTRING(TRIM(nombre_completo), 2))
                        )
                    END
                ")
            ]);

            Alumno::get()->each(fn($a) => $a->update(['matricula' => strtoupper(trim($a->matricula))]));

            $duplicados = Alumno::select('matricula')
                ->groupBy('matricula')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('matricula');

            foreach ($duplicados as $matricula) {
                Alumno::where('matricula', $matricula)
                    ->orderBy('id')
                    ->skip(1)
                    ->delete();
            }

            Alumno::whereNotIn('estado', ['activo', 'inactivo'])
                ->update(['estado' => 'activo']);
        });
    }

    private function cleanPagos(): void
    {
        DB::transaction(function () {
            Pago::where('monto', '<', 0)->update([
                'monto' => DB::raw("ABS(monto)")
            ]);

            // no deberia eliminarse xD
            Pago::whereNull('matricula')->delete();
        });
    }

    private function cleanAdeudos(): void
    {
        DB::transaction(function () {
            Adeudo::where('monto', '<', 0)->update([
                'monto' => DB::raw("ABS(monto)")
            ]);

            //
            Adeudo::whereNull('alumno_id')->delete();
        });
    }

    private function cleanInscripciones(): void
    {
        DB::transaction(function () {
            Inscripcion::whereNotIn('estado', ['activo', 'inactivo'])
                ->update(['estado' => 'activo']);

            Inscripcion::whereColumn('fecha_termino', '<', 'fecha_inscripcion')
                ->update([
                    'fecha_termino' => DB::raw("DATE_ADD(NOW(), INTERVAL 6 MONTH)")
                ]);
        });
    }


}
