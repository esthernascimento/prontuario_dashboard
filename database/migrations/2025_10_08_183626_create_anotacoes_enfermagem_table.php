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
        Schema::create('tbAnotacaoEnfermagem', function (Blueprint $table) {
            $table->id('idAnotacao');

            // CHAVES ESTRANGEIRAS AJUSTADAS:
            
            // 1. Chave para Paciente: assume que tbPaciente tem 'idPaciente'
            $table->foreignId('idPacienteFK')->constrained('tbPaciente', 'idPaciente');
            
            // 2. Chave para Enfermeiro: CORRIGIDO para usar a chave primária correta 'idEnfermeiroPK'
            // Você pode usar o método `foreign` e `references` para especificar explicitamente a coluna.
            $table->unsignedBigInteger('idEnfermeiroFK');
            $table->foreign('idEnfermeiroFK')->references('idEnfermeiroPK')->on('tbEnfermeiro');

            // Dados Principais da Anotação
            $table->dateTime('data_hora');
            $table->string('tipo_registro', 50);
            $table->string('unidade_atendimento', 255);
            $table->text('descricao');
            
            // Sinais Vitais (Opcionais)
            $table->decimal('temperatura', 4, 1)->nullable(); 
            $table->string('pressao_arterial', 20)->nullable();
            $table->integer('frequencia_cardiaca')->nullable();
            $table->integer('saturacao')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbAnotacaoEnfermagem');
    }
};