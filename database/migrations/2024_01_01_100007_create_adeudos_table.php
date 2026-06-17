<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adeudos', function (Blueprint $table) {
            $table->id();
            $table->integer('alumno_id');
            $table->text('nombre');
            $table->float('monto', 11, 2);
            $table->integer('plazos');
            $table->date('desde')->nullable();
            $table->integer('dias')->default(31);
            $table->integer('divisa_id')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adeudos');
    }
};
