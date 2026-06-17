<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Adeudo;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        // Alumno normal
        $a1 = Alumno::create([
            'matricula' => 'UNI2024001',
            'nombre_completo' => 'Juan Pérez',
            'apat' => 'Pérez',
            'amat' => 'García',
            'curp' => 'PEGJ901012HDFRRN09',
            'email' => 'juan@test.com',
            'telefono' => '5551234567',
            'sede_id' => 1,
            'organizacion_id' => 1,
            'fecha_nacimiento' => '1990-01-01',
            'fecha_inscripcion' => '2024-01-15',
            'estado' => 'activo',
            'tags' => json_encode(['beca_50']),
        ]);

        Inscripcion::create([
            'alumno_id' => $a1->id,
            'programa_id' => 1,
            'sede_id' => 1,
            'estado' => 'activo',
            'fecha_inscripcion' => '2024-01-15',
        ]);

        Pago::create([
            'matricula' => 'UNI2024001',
            'concepto' => 'Inscripción',
            'monto' => '1500.00',
            'fecha_pago' => '2024-01-15',
            'metodo' => 'transferencia',
            'sede_id' => 1,
            'estado' => 'activo',
        ]);

        // BUG #37: Alumno con sede_id inexistente (99)
        $a2 = Alumno::create([
            'matricula' => 'UNI2024002',
            'nombre_completo' => 'María López',
            'apat' => 'López',
            'amat' => 'Hernández',
            'curp' => 'LOHM950505MDFRNR05',
            'email' => 'maria@test.com',
            'telefono' => '5559876543',
            'sede_id' => 99,
            'organizacion_id' => 1,
            'fecha_nacimiento' => '1995-05-05',
            'fecha_inscripcion' => '2024-02-01',
            'estado' => 'activo',
            'tags' => null,
        ]);

        Inscripcion::create([
            'alumno_id' => $a2->id,
            'programa_id' => 2,
            'sede_id' => 99,
            'estado' => 'activo',
            'fecha_inscripcion' => '2024-02-01',
        ]);

        // BUG #38: Pago con monto negativo (texto)
        Pago::create([
            'matricula' => 'UNI2024002',
            'concepto' => 'Colegiatura',
            'monto' => '-2000.00',
            'fecha_pago' => '2024-02-01',
            'metodo' => 'tarjeta',
            'sede_id' => 99,
            'estado' => 'activo',
        ]);

        // BUG #39: Alumno sin inscripción (estado inactivo pero sin inscripciones)
        Alumno::create([
            'matricula' => 'UNI2024003',
            'nombre_completo' => 'Carlos Ruiz',
            'apat' => 'Ruiz',
            'amat' => 'Santos',
            'curp' => 'RUIS880808HDFNNS08',
            'email' => 'carlos@test.com',
            'telefono' => '5550001111',
            'sede_id' => 1,
            'organizacion_id' => 1,
            'fecha_nacimiento' => '1988-08-08',
            'fecha_inscripcion' => null,
            'estado' => 'inactivo',
            'tags' => json_encode(['baja_temporal']),
        ]);

        Adeudo::create([
            'alumno_id' => $a1->id,
            'nombre' => 'Colegiatura marzo',
            'monto' => 1200.00,
            'plazos' => 1,
            'desde' => '2024-03-01',
            'dias' => 31,
            'divisa_id' => 1,
        ]);
    }
}
