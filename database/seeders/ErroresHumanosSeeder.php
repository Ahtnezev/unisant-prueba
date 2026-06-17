<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Adeudo;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ErroresHumanosSeeder extends Seeder
{
    /**
     * Inserta datos con errores humanos reales para que el candidato
     * identifique problemas de calidad de datos, integridad y consistencia.
     */
    public function run(): void
    {
        $insertados = 0;
        $fallidos = 0;

        $registros = [
            // 1. ALUMNOS CON DATOS INVÁLIDOS
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024004',
                    'nombre_completo' => 'Pedro Infante',
                    'apat' => 'Infante',
                    'amat' => 'Cruz',
                    'curp' => 'PEDRO12345',
                    'email' => 'pedro.email.sin.arroba',
                    'telefono' => 'abc-123-defg',
                    'sede_id' => 1,
                    'organizacion_id' => 1,
                    'fecha_nacimiento' => '2025-01-01',
                    'fecha_inscripcion' => '2023-12-31',
                    'estado' => 'activo',
                    'tags' => null,
                ],
            ],
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024001',
                    'nombre_completo' => 'Juan Pérez García (Segundo registro)',
                    'apat' => 'Pérez',
                    'amat' => 'García',
                    'curp' => 'PEGJ901012HDFRRN09',
                    'email' => 'juan.duplicado@test.com',
                    'telefono' => '5550000000',
                    'sede_id' => 2,
                    'organizacion_id' => 1,
                    'fecha_nacimiento' => '1990-01-01',
                    'fecha_inscripcion' => '2024-03-01',
                    'estado' => 'activo',
                    'tags' => null,
                ],
            ],
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024005',
                    'nombre_completo' => '',
                    'apat' => '',
                    'amat' => '',
                    'curp' => null,
                    'email' => null,
                    'telefono' => null,
                    'sede_id' => 1,
                    'organizacion_id' => null,
                    'fecha_nacimiento' => null,
                    'fecha_inscripcion' => null,
                    'estado' => 'activo',
                    'tags' => null,
                ],
            ],
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024006',
                    'nombre_completo' => '  aNA mARía  lÓPez  ',
                    'apat' => 'lóPez',
                    'amat' => 'hERNÁNdez',
                    'curp' => 'LOHA850101MDFRRN00',
                    'email' => '  ana.maria@test.com  ',
                    'telefono' => '555-1234-567',
                    'sede_id' => 1,
                    'organizacion_id' => 1,
                    'fecha_nacimiento' => '1985-01-01',
                    'fecha_inscripcion' => '2024-05-01',
                    'estado' => 'activo',
                    'tags' => null,
                ],
            ],
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024007',
                    'nombre_completo' => 'Laura Inactiva',
                    'apat' => 'Inactiva',
                    'amat' => 'Martínez',
                    'curp' => 'INML900101MDFRRN00',
                    'email' => 'laura@test.com',
                    'telefono' => '5559998888',
                    'sede_id' => 1,
                    'organizacion_id' => 1,
                    'fecha_nacimiento' => '1990-01-01',
                    'fecha_inscripcion' => '2024-06-01',
                    'estado' => 'inactivo',
                    'tags' => null,
                ],
            ],
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024009',
                    'nombre_completo' => 'José María Niño',
                    'apat' => 'Niño',
                    'amat' => 'García',
                    'curp' => 'NIGJ850101HDFRRN00',
                    'email' => 'jose.maria.nino@test.com',
                    'telefono' => '5551234567',
                    'sede_id' => 1,
                    'organizacion_id' => 1,
                    'fecha_nacimiento' => '1985-01-01',
                    'fecha_inscripcion' => '2024-06-01',
                    'estado' => 'activo',
                    'tags' => json_encode(['especialidad: niños', 'grado: 1°']),
                ],
            ],
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024010',
                    'nombre_completo' => 'Bebé Genio',
                    'apat' => 'Genio',
                    'amat' => 'Bebé',
                    'curp' => 'BEBG20240101HDFRRN00',
                    'email' => 'bebe@test.com',
                    'telefono' => '5550000000',
                    'sede_id' => 1,
                    'organizacion_id' => 1,
                    'fecha_nacimiento' => '2024-01-01',
                    'fecha_inscripcion' => '2024-06-01',
                    'estado' => 'activo',
                    'tags' => null,
                ],
            ],
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024011',
                    'nombre_completo' => 'Estado Raro',
                    'apat' => 'Raro',
                    'amat' => 'Estado',
                    'curp' => 'RAES900101HDFRRN00',
                    'email' => 'raro@test.com',
                    'telefono' => '5551112222',
                    'sede_id' => 1,
                    'organizacion_id' => 1,
                    'fecha_nacimiento' => '1990-01-01',
                    'fecha_inscripcion' => '2024-06-01',
                    'estado' => 'en_proceso_baja',
                    'tags' => null,
                ],
            ],
            [
                'model' => Alumno::class,
                'data' => [
                    'matricula' => 'UNI2024008',
                    'nombre_completo' => 'Roberto Cerrado',
                    'apat' => 'Cerrado',
                    'amat' => 'Sede',
                    'curp' => 'CERS950505HDFRRN00',
                    'email' => 'roberto@test.com',
                    'telefono' => '5554443333',
                    'sede_id' => null, // Se asignará después a sede inactiva
                    'organizacion_id' => 1,
                    'fecha_nacimiento' => '1995-05-05',
                    'fecha_inscripcion' => '2024-06-01',
                    'estado' => 'activo',
                    'tags' => null,
                ],
            ],

            // 5. SEDES CON PROBLEMAS
            [
                'model' => Sede::class,
                'data' => [
                    'nombre' => '   ',
                    'clave' => '   ',
                    'direccion' => 'Dirección desconocida',
                    'meta_alumnos' => 0,
                    'activa' => true,
                ],
            ],
            [
                'model' => Sede::class,
                'data' => [
                    'nombre' => 'Sede Fantasma',
                    'clave' => 'FAN',
                    'direccion' => 'Calle 99',
                    'meta_alumnos' => -100,
                    'activa' => false,
                ],
            ],
            [
                'model' => Sede::class,
                'data' => [
                    'nombre' => 'Sede Cerrada',
                    'clave' => 'CER',
                    'direccion' => 'Calle 100',
                    'meta_alumnos' => 100,
                    'activa' => false,
                ],
            ],

            // 6. USUARIOS CON PROBLEMAS
            [
                'model' => User::class,
                'data' => [
                    'name' => 'Usuario Mal',
                    'email' => 'email-invalido',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'nivel_id' => 2,
                    'sede_id' => 1,
                    'cargo' => 'Coordinador',
                ],
            ],
            [
                'model' => User::class,
                'data' => [
                    'name' => 'Usuario Sede Cerrada',
                    'email' => 'sede.cerrada@unisant.test',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'nivel_id' => 2,
                    'sede_id' => null, // Se asignará después
                    'cargo' => 'Coordinador',
                ],
            ],

            // 7. PROGRAMAS CON PROBLEMAS
            [
                'model' => Programa::class,
                'data' => [
                    'nombre' => '',
                    'objetivo' => 'Sin objetivo',
                    'inscripcion' => 0,
                    'precio_materia' => 0,
                    'precio_materia_reprobada' => 0,
                    'plazos_colegiatura' => 0,
                    'minima' => 0,
                    'limite_pago' => 0,
                    'modalidad_id' => 1,
                    'rvoe_id' => null,
                    'tags' => '{"malformado":}',
                ],
            ],
            [
                'model' => Programa::class,
                'data' => [
                    'nombre' => 'Programa con precios raros',
                    'objetivo' => 'Test',
                    'inscripcion' => -100.00,
                    'precio_materia' => -500.00,
                    'precio_materia_reprobada' => -250.00,
                    'plazos_colegiatura' => 12,
                    'minima' => 6,
                    'limite_pago' => 5,
                    'modalidad_id' => 1,
                ],
            ],
            [
                'model' => Programa::class,
                'data' => [
                    'nombre' => 'Programa tags mal',
                    'objetivo' => 'Test',
                    'inscripcion' => 1000,
                    'precio_materia' => 800,
                    'precio_materia_reprobada' => 400,
                    'plazos_colegiatura' => 8,
                    'minima' => 4,
                    'limite_pago' => 5,
                    'modalidad_id' => 1,
                    'tags' => 'diplomado,admin',
                ],
            ],
        ];

        // Insertar con manejo de excepciones para que las unique constraint fallen pero el seeder continúe
        foreach ($registros as $registro) {
            try {
                $model = $registro['model'];
                $model::create($registro['data']);
                $insertados++;
            } catch (\Exception $e) {
                $fallidos++;
                // Intencionalmente ignoramos para que la prueba tenga datos parciales
            }
        }

        // Post-proceso: inscripciones y relaciones que dependen de IDs existentes
        $sedeCerrada = Sede::where('clave', 'CER')->first();
        if ($sedeCerrada) {
            // Actualizar alumno Roberto a sede cerrada
            Alumno::where('matricula', 'UNI2024008')->update(['sede_id' => $sedeCerrada->id]);
            // Actualizar usuario a sede cerrada
            User::where('email', 'sede.cerrada@unisant.test')->update(['sede_id' => $sedeCerrada->id]);
        }

        // Inscripciones con problemas
        $aInactivo = Alumno::where('matricula', 'UNI2024007')->first();
        $relacionales = [
            ['model' => Inscripcion::class, 'data' => ['alumno_id' => $aInactivo?->id, 'programa_id' => 1, 'sede_id' => 1, 'estado' => 'activo', 'fecha_inscripcion' => '2024-06-01']],
            ['model' => Inscripcion::class, 'data' => ['alumno_id' => $aInactivo?->id, 'programa_id' => 999, 'sede_id' => 1, 'estado' => 'activo', 'fecha_inscripcion' => '2024-06-01']],
            ['model' => Inscripcion::class, 'data' => ['alumno_id' => 1, 'programa_id' => 2, 'sede_id' => 1, 'estado' => 'activo', 'fecha_inscripcion' => '2024-06-01', 'fecha_termino' => '2024-01-01', 'observaciones' => 'Inscripción con fechas inconsistentes']],
            ['model' => Inscripcion::class, 'data' => ['alumno_id' => 1, 'programa_id' => 1, 'sede_id' => 1, 'estado' => 'activo', 'fecha_inscripcion' => '2024-06-15']],
            ['model' => Inscripcion::class, 'data' => ['alumno_id' => 1, 'programa_id' => 1, 'sede_id' => null, 'estado' => 'activo', 'fecha_inscripcion' => '2024-06-01']],
        ];

        foreach ($relacionales as $rel) {
            try {
                $rel['model']::create($rel['data']);
                $insertados++;
            } catch (\Exception $e) {
                $fallidos++;
            }
        }

        // Pagos con problemas
        $pagos = [
            ['model' => Pago::class, 'data' => ['matricula' => 'UNI2024001', 'concepto' => 'Inscripción', 'monto' => '1,500.00', 'fecha_pago' => '2024-06-15', 'metodo' => 'efectivo', 'sede_id' => 1, 'estado' => 'activo']],
            ['model' => Pago::class, 'data' => ['matricula' => 'UNI2024001', 'concepto' => 'Colegiatura', 'monto' => '0.00', 'fecha_pago' => '2024-06-15', 'metodo' => 'efectivo', 'sede_id' => 1, 'estado' => 'activo']],
            ['model' => Pago::class, 'data' => ['matricula' => 'UNI2024004', 'concepto' => '', 'monto' => '500.00', 'fecha_pago' => '2024-06-10', 'metodo' => 'transferencia', 'sede_id' => 1, 'estado' => 'activo']],
            ['model' => Pago::class, 'data' => ['matricula' => 'UNI2024006', 'concepto' => 'Colegiatura', 'monto' => '1200.00', 'fecha_pago' => '2027-01-01', 'metodo' => 'efectivo', 'sede_id' => 1, 'estado' => 'activo']],
            ['model' => Pago::class, 'data' => ['matricula' => 'UNI9999999', 'concepto' => 'Inscripción', 'monto' => '1500.00', 'fecha_pago' => '2024-06-01', 'metodo' => 'efectivo', 'sede_id' => 1, 'estado' => 'activo']],
            ['model' => Pago::class, 'data' => ['matricula' => 'UNI2024001', 'concepto' => 'Extra', 'monto' => '100.00', 'fecha_pago' => '2024-06-01', 'metodo' => 'efectivo', 'sede_id' => 1, 'estado' => 'pendiente_pago']],
            ['model' => Pago::class, 'data' => ['matricula' => 'UNI2024001', 'concepto' => 'Inscripción', 'monto' => '1500.00', 'fecha_pago' => '2024-01-15', 'metodo' => 'transferencia', 'sede_id' => 1, 'estado' => 'activo']],
            ['model' => Pago::class, 'data' => ['matricula' => 'UNI2024001', 'concepto' => 'Colegiatura', 'monto' => '1200.00', 'fecha_pago' => '2024-06-01', 'metodo' => 'efectivo', 'sede_id' => 1, 'estado' => 'activo', 'nota' => str_repeat('A', 5000)]],

        ];
        foreach ($pagos as $pago) {
            try {
                $pago['model']::create($pago['data']);
                $insertados++;
            } catch (\Exception $e) {
                $fallidos++;
            }
        }

        // Adeudos con problemas
        $adeudos = [
            ['model' => Adeudo::class, 'data' => ['alumno_id' => 1, 'nombre' => 'Colegiatura julio', 'monto' => -500.00, 'plazos' => 1, 'desde' => '2024-07-01', 'dias' => 31, 'divisa_id' => 1]],
            ['model' => Adeudo::class, 'data' => ['alumno_id' => 1, 'nombre' => 'Colegiatura agosto', 'monto' => 1200.00, 'plazos' => 0, 'desde' => '2024-08-01', 'dias' => 31, 'divisa_id' => 1]],
            ['model' => Adeudo::class, 'data' => ['alumno_id' => 999, 'nombre' => 'Colegiatura fantasma', 'monto' => 1200.00, 'plazos' => 1, 'desde' => '2024-09-01', 'dias' => 31, 'divisa_id' => 1]],
        ];
        foreach ($adeudos as $adeudo) {
            try {
                $adeudo['model']::create($adeudo['data']);
                $insertados++;
            } catch (\Exception $e) {
                $fallidos++;
            }
        }

        $this->command->info("Errores humanos: {$insertados} insertados, {$fallidos} fallidos por constraints (intencional).");
        $this->command->info('Inconsistencias listas: H1-H37.');
    }
}
