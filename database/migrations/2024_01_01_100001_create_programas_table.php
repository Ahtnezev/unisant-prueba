<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('objetivo')->nullable();
            $table->float('inscripcion', 11, 2)->default(0);
            $table->float('precio_materia', 11, 2)->default(0);
            $table->float('precio_materia_reprobada', 11, 2)->default(0);
            $table->integer('plazos_colegiatura')->nullable();
            $table->integer('minima')->default(6);
            $table->integer('limite_pago')->default(5);
            $table->integer('modalidad_id')->default(1);
            $table->integer('rvoe_id')->nullable();
            $table->text('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programas');
    }
};
