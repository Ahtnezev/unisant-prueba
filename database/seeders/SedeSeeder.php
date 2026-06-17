<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sede;

class SedeSeeder extends Seeder
{
    public function run(): void
    {
        Sede::create(['nombre' => 'Sede Central', 'clave' => 'CEN', 'direccion' => 'Calle 1', 'meta_alumnos' => 500, 'activa' => true]);
        Sede::create(['nombre' => 'Sede Norte', 'clave' => 'NOR', 'direccion' => 'Calle 2', 'meta_alumnos' => 300, 'activa' => true]);
        Sede::create(['nombre' => 'Sede Sur', 'clave' => 'SUR', 'direccion' => 'Calle 3', 'meta_alumnos' => 200, 'activa' => false]);
    }
}
