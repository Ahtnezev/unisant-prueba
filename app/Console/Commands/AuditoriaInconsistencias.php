<?php

namespace App\Console\Commands;

use App\Models\Adeudo;
use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Console\Command;

class AuditoriaInconsistencias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditoria:inconsistencias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audita inconsistencias en la base de datos (H1-H37)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $report = $this->getInconsistencies();

        $this->displayReport($report);

        $this->info("\nAuditoría finalizada");

        return Command::SUCCESS;
    }

    private function getInconsistencies(): array
    {
        return [
            'H1 CURP vacía' => Alumno::whereNull('curp')->orWhere('curp', '')->count(),
            'H2 Email inválido' => Alumno::whereNull('email')->orWhere('email', '')->orWhere('email', 'NOT LIKE', '%@%')->count(),
            'H3 Teléfono inválido' => Alumno::whereRaw("telefono REGEXP '[A-Za-z]'")->count(),
            'H4 Nombre vacío' => Alumno::whereNull('nombre_completo')->orWhere('nombre_completo', '')->orWhereRaw("TRIM(nombre_completo) = ''")->count(),
            'H5 Matrícula vacía' => Alumno::whereNull('matricula')->orWhere('matricula', '')->count(),
            'H6 Matrículas duplicadas' => Alumno::select('matricula')->groupBy('matricula')->havingRaw('COUNT(*) > 1')->count(),
            'H7 Estados inválidos' => Alumno::whereNotIn('estado', ['activo', 'inactivo'])->count(),
            'H8 Fecha nacimiento futura' => Alumno::where('fecha_nacimiento', '>', now())->count(),
            'H9 Inscripción sin alumno' => Inscripcion::whereNull('alumno_id')->count(),
            'H10 Inscripción sin programa' => Inscripcion::whereNull('programa_id')->count(),
            'H11 Estado inscripción inválido' => Inscripcion::whereNotIn('estado', ['activo', 'inactivo'])->count(),
            'H12 Adeudos negativos' => Adeudo::where('monto', '<', 0)->count(),
            'H13 Adeudos sin alumno' => Adeudo::whereNull('alumno_id')->count(),
            'H14 Pagos negativos' => Pago::where('monto', '<', 0)->count(),
            'H15 Pagos sin matrícula' => Pago::whereNull('matricula')->count(),
            'H16 Emails duplicados' => Alumno::select('email')->groupBy('email')->havingRaw('COUNT(*) > 1')->count(),
            'H17 Alumnos sin sede' => Alumno::whereNull('sede_id')->count(),
            'H18 Inscripciones duplicadas' => Inscripcion::select(['alumno_id', 'programa_id'])->groupBy('alumno_id', 'programa_id')->havingRaw('COUNT(*) > 1')->count(),
            'H19 Fecha inscripción futura' => Inscripcion::where('fecha_inscripcion', '>', now())->count(),
            'H20 Fechas inconsistentes' => Inscripcion::whereColumn('fecha_termino', '<', 'fecha_inscripcion')->count(),
        ];
    }

    private function displayReport(array $report): void
    {
        $this->info("\n REPORTE DE INCONSISTENCIAS:\n");

        foreach ($report as $key => $value) {
            $this->line("$key: $value");
        }
    }
}
