<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('clave')->nullable();
            $table->string('alias')->nullable();
            $table->text('seriacion')->nullable();
            $table->float('creditos')->nullable();
            $table->integer('planescolar_id')->nullable();
            $table->integer('tipomateria_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
