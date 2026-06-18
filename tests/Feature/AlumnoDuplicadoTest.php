<?php

namespace Tests\Feature;

use App\Models\Alumno;
use App\Models\Sede;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AlumnoDuplicadoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function no_se_puede_registrar_alumno_con_matricula_duplicada()
    {
        $sede = Sede::create([
            'nombre' => 'Sede Principal',
            'activa' => true,
        ]);

        Alumno::create([
            'matricula' => 'A2024001',
            'nombre_completo' => 'Juan Pérez',
            'apat' => 'Pérez',
            'amat' => 'García',
            'curp' => 'PEGJ010101HDFRRL01',
            'email' => 'juan@test.com',
            'telefono' => '5512345678',
            'sede_id' => $sede->id,
            'fecha_nacimiento' => '2001-01-01',
            'estado' => 'activo',
        ]);

        $response = $this->post('/alumnos', [
            'matricula' => 'A2024001', // misma matricula
            'nombre_completo' => 'María López',
            'apat' => 'López',
            'amat' => 'Gómez',
            'curp' => 'LOGJ020202HDFRRL02',
            'email' => 'maria@test.com',
            'telefono' => '5512345679',
            'sede_id' => $sede->id,
            'fecha_nacimiento' => '2002-02-02',
        ]);

        $response->assertSessionHasErrors('matricula');

        $this->assertEquals(1, Alumno::where('matricula', 'A2024001')->count());
    }

    /** @test */
    public function no_se_puede_registrar_alumno_con_curp_duplicada()
    {
        $sede = Sede::create([
            'nombre' => 'Sede Principal',
            'activa' => true,
        ]);

        Alumno::create([
            'matricula' => 'A2024001',
            'nombre_completo' => 'Juan Pérez',
            'apat' => 'Pérez',
            'amat' => 'García',
            'curp' => 'PEGJ010101HDFRRL01',
            'email' => 'juan@test.com',
            'telefono' => '5512345678',
            'sede_id' => $sede->id,
            'fecha_nacimiento' => '2001-01-01',
            'estado' => 'activo',
        ]);

        $response = $this->post('/alumnos', [
            'matricula' => 'A2024002',
            'nombre_completo' => 'María López',
            'apat' => 'López',
            'amat' => 'Gómez',
            'curp' => 'PEGJ010101HDFRRL01', // misma curp
            'email' => 'maria@test.com',
            'telefono' => '5512345679',
            'sede_id' => $sede->id,
            'fecha_nacimiento' => '2002-02-02',
        ]);

        $response->assertSessionHasErrors('curp');

        $this->assertEquals(1, Alumno::where('curp', 'PEGJ010101HDFRRL01')->count());
    }

    /** @test */
    public function no_se_puede_registrar_alumno_con_email_duplicado()
    {
        $sede = Sede::create([
            'nombre' => 'Sede Principal',
            'activo' => true,
        ]);

        Alumno::create([
            'matricula' => 'A2024001',
            'nombre_completo' => 'Juan Pérez',
            'apat' => 'Pérez',
            'amat' => 'García',
            'curp' => 'PEGJ010101HDFRRL01',
            'email' => 'juan@test.com',
            'telefono' => '5512345678',
            'sede_id' => $sede->id,
            'fecha_nacimiento' => '2001-01-01',
            'estado' => 'activo',
        ]);

        $response = $this->post('/alumnos', [
            'matricula' => 'A2024002',
            'nombre_completo' => 'María López',
            'apat' => 'López',
            'amat' => 'Gómez',
            'curp' => 'LOGJ020202HDFRRL02',
            'email' => 'juan@test.com', // mismo email
            'telefono' => '5512345679',
            'sede_id' => $sede->id,
            'fecha_nacimiento' => '2002-02-02',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertEquals(1, Alumno::where('email', 'juan@test.com')->count());
    }
}
