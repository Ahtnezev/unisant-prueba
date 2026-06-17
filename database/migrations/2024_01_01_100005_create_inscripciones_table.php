<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->integer('alumno_id');
            $table->integer('programa_id');
            $table->integer('sede_id');
            $table->text('estado')->default('activo');
            $table->date('fecha_inscripcion')->nullable();
            $table->date('fecha_termino')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
