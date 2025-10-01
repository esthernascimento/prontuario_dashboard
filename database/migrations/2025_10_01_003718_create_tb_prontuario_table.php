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
        Schema::create('tbProntuario', function (Blueprint $table) {
            $table->id('idProntuarioPK');
            
            // CORREÇÃO CRUCIAL AQUI:
            // Apontando para a tabela 'tbPaciente' e para a coluna 'idPacientePK' corretas.
            $table->foreignId('idPacienteFK')->unique()->constrained('tbPaciente', 'idPacientePK');
            
            $table->date('dataAbertura');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbProntuario');
    }
};
