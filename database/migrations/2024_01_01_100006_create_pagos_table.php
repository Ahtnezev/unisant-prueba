<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('matricula');
            $table->text('concepto');
            $table->decimal('monto', 11, 2);
            $table->date('fecha_pago');
            $table->text('metodo')->nullable();
            $table->integer('sede_id')->nullable();
            $table->integer('conciliacion_id')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'completed', 'pending'])->default('activo');
            $table->text('nota')->nullable();
            $table->text('chunk')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('matricula');
            $table->index('sede_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
