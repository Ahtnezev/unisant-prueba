<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Programa;
use App\Models\Materia;

class ProgramaSeeder extends Seeder
{
    public function run(): void
    {
        $p1 = Programa::create([
            'nombre' => 'Licenciatura en Administración',
            'objetivo' => 'Formar administradores',
            'inscripcion' => 1500.00,
            'precio_materia' => 1200.00,
            'precio_materia_reprobada' => 600.00,
            'plazos_colegiatura' => 12,
            'minima' => 6,
            'limite_pago' => 5,
            'modalidad_id' => 1,
            'rvoe_id' => null,
            'tags' => json_encode(['admin', 'licenciatura']),
        ]);

        $p2 = Programa::create([
            'nombre' => 'Maestría en Educación',
            'objetivo' => 'Formar docentes',
            'inscripcion' => 2500.00,
            'precio_materia' => 2000.00,
            'precio_materia_reprobada' => 1000.00,
            'plazos_colegiatura' => 8,
            'minima' => 4,
            'limite_pago' => 10,
            'modalidad_id' => 2,
            'rvoe_id' => null,
            'tags' => null, // BUG #35: tags null cuando otros tienen JSON
        ]);

        // BUG #36: Programa sin precio_materia (queda en default 0)
        $p3 = Programa::create([
            'nombre' => 'Diplomado en Finanzas',
            'objetivo' => 'Finanzas básicas',
            'inscripcion' => 500.00,
            'precio_materia' => 0,
            'precio_materia_reprobada' => 0,
            'plazos_colegiatura' => null,
            'minima' => 3,
            'limite_pago' => 3,
            'modalidad_id' => 1,
        ]);

        $m1 = Materia::create(['nombre' => 'Matemáticas I', 'clave' => 'MAT101', 'creditos' => 8, 'planescolar_id' => 1, 'tipomateria_id' => 1]);
        $m2 = Materia::create(['nombre' => 'Contabilidad', 'clave' => 'CON102', 'creditos' => 6, 'planescolar_id' => 1, 'tipomateria_id' => 1]);
        $m3 = Materia::create(['nombre' => 'Pedagogía', 'clave' => 'PED201', 'creditos' => 10, 'planescolar_id' => 2, 'tipomateria_id' => 2]);

        $p1->materias()->attach([$m1->id, $m2->id]);
        $p2->materias()->attach([$m3->id]);
    }
}
