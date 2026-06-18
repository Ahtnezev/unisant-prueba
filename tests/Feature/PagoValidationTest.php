<?php

namespace Tests\Feature;

use App\Models\Pago;
use App\Models\Alumno;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagoValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_pago_con_monto_negativo_es_rechazado()
    {
        // Arrange
        $alumno = Alumno::factory()->create();

        // Act
        try {
            Pago::create([
                'alumno_id' => $alumno->id,
                'monto' => -100.00,
                'matricula' => 'A12345',
                'fecha_pago' => now(),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Assert
            $this->assertDatabaseMissing('pagos', ['monto' => -100.00]);
            return;
        }

        $this->fail('Se esperaba una excepción pero no ocurrió.');
    }


    /** @test */
    public function un_pago_con_monto_cero_es_rechazado()
    {
        // Arrange
        $alumno = Alumno::factory()->create();

        // Act
        try {
            Pago::create([
                'alumno_id' => $alumno->id,
                'monto' => 0,
                'matricula' => 'A12345',
                'fecha_pago' => now(),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Assert
            $this->assertDatabaseMissing('pagos', ['monto' => 0]);
            return;
        }

        $this->fail('Se esperaba una excepción pero no ocurrió.');
    }

    /** @test */
    public function un_pago_con_monto_negativo_es_rechazado_desde_el_modelo()
    {
        // Arrange
        $alumno = Alumno::factory()->create();

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);

        Pago::create([
            'alumno_id' => $alumno->id,
            'monto' => -500.00,
            'matricula' => 'A12345',
            'fecha_pago' => now(),
        ]);
    }

    /** @test */
    public function un_pago_con_monto_negativo_en_actualizacion_es_rechazado()
    {
        // Arrange
        $alumno = Alumno::factory()->create();
        $pago = Pago::create([
            'alumno_id' => $alumno->id,
            'monto' => 100.00,
            'matricula' => 'A12345',
            'fecha_pago' => now(),
        ]);

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);

        $pago->update(['monto' => -50.00]);
    }
}
