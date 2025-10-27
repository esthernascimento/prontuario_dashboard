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
        Schema::table('tbConsulta', function (Blueprint $table) {
            // Diz ao banco que essas colunas podem aceitar NULL
            // O ->change() modifica a coluna existente
            
            $table->unsignedBigInteger('idProntuarioFK')->nullable()->change();
            $table->unsignedBigInteger('idMedicoFK')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbConsulta', function (Blueprint $table) {
            // Opcional: reverte para NOT NULL
            $table->unsignedBigInteger('idProntuarioFK')->nullable(false)->change();
            $table->unsignedBigInteger('idMedicoFK')->nullable(false)->change();
        });
    }
};
