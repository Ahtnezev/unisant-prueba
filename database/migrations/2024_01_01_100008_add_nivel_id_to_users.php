<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('nivel_id')->nullable()->default(2)->after('password');
            $table->integer('sede_id')->nullable()->after('nivel_id');
            $table->text('cargo')->nullable()->after('sede_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nivel_id', 'sede_id', 'cargo']);
        });
    }
};
