<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->text('matricula');
            $table->text('nombre_completo');
            $table->text('apat');
            $table->text('amat');
            $table->text('curp')->nullable();
            $table->text('email')->nullable();
            $table->text('telefono')->nullable();
            $table->integer('sede_id');
            $table->integer('organizacion_id')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_inscripcion')->nullable();
            $table->text('estado')->default('activo');
            $table->text('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
