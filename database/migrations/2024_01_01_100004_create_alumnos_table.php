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
            $table->string('matricula');
            $table->string('nombre_completo');
            $table->string('apat');
            $table->string('amat');
            $table->string('curp')->unique();
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->integer('sede_id');
            $table->integer('organizacion_id')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_inscripcion')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'suspendido'])->default('activo');
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
