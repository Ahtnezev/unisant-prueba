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
            $table->text('matricula');
            $table->text('concepto');
            $table->text('monto');
            $table->text('fecha_pago');
            $table->text('metodo')->nullable();
            $table->integer('sede_id')->nullable();
            $table->integer('conciliacion_id')->nullable();
            $table->text('estado')->default('activo');
            $table->text('nota')->nullable();
            $table->text('chunk')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
