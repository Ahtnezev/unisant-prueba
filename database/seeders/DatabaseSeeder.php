<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SedeSeeder::class,
            ProgramaSeeder::class,
            AlumnoSeeder::class,
            ErroresHumanosSeeder::class,
        ]);
    }
}
