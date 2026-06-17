<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_deudas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('filtros')->nullable();
            $table->text('resultado')->nullable();
            $table->text('estado')->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_deudas');
    }
};
