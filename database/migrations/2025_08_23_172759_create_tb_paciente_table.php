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
        Schema::create('tbPaciente', function (Blueprint $table) {
            $table->id('idPacientePK');

            // Dados do Paciente
            $table->string('nomePaciente');
            $table->string('cpfPaciente', 14)->unique()->nullable();
            $table->date('dataNascPaciente')->nullable();
            $table->string('cartaoSusPaciente', 20)->nullable()->unique();
            $table->string('generoPaciente')->nullable();
            $table->string('fotoPaciente')->nullable();
            $table->string('telefonePaciente', 20)->nullable();

            // Endereço
            $table->string('logradouroPaciente')->nullable();
            $table->string('numLogradouroPaciente')->nullable();
            $table->string('cepPaciente', 9)->nullable();
            $table->string('bairroPaciente')->nullable();
            $table->string('cidadePaciente')->nullable();
            $table->string('ufPaciente', 2)->nullable();
            
            // AUTENTICAÇÃO (AGORA DENTRO DESTA TABELA)
            $table->string('emailPaciente')->unique();
            $table->string('senhaPaciente');
            
            // Controlo
            $table->boolean('statusPaciente')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbPaciente');
    }
};

