<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbUsuario', function (Blueprint $table) {
    
            $table->string('nomeUsuario')->after('idUsuarioPK');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbUsuario', function (Blueprint $table) {
            $table->dropColumn('nomeUsuario');
        });
    }
};
