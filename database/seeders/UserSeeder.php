<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@unisant.test',
            'password' => Hash::make('password'),
            'nivel_id' => 1,
            'sede_id' => null,
            'cargo' => 'Admin',
        ]);

        User::create([
            'name' => 'Sede Norte',
            'email' => 'sede@unisant.test',
            'password' => Hash::make('password'),
            'nivel_id' => 2,
            'sede_id' => 1,
            'cargo' => 'Coordinador',
        ]);

        User::create([
            'name' => 'Control Escolar',
            'email' => 'ce@unisant.test',
            'password' => Hash::make('password'),
            'nivel_id' => 3,
            'sede_id' => null,
            'cargo' => 'Analista',
        ]);

        // BUG #34: Usuario sin nivel_id (queda en default 2, pero cargo nulo)
        User::create([
            'name' => 'Sin Rol Definido',
            'email' => 'sinrol@unisant.test',
            'password' => Hash::make('password'),
            'nivel_id' => null,
            'sede_id' => null,
            'cargo' => null,
        ]);
    }
}
