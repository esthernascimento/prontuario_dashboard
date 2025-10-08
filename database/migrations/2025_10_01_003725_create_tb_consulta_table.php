<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbConsulta', function (Blueprint $table) {
            $table->id('idConsultaPK');

            // FK -> tbProntuario.idProntuarioPK (muitas consultas por prontuário)
            $table->foreignId('idProntuarioFK')
                  ->constrained('tbProntuario', 'idProntuarioPK')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // FK -> tbMedico.idMedicoPK
            $table->foreignId('idMedicoFK')
                  ->constrained('tbMedico', 'idMedicoPK')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Dados do médico (denormalizados para histórico)
            $table->string('nomeMedico', 255);
            $table->string('crmMedico', 50);

            // FK -> tbEnfermeiro.idEnfermeiroPK (nullable; se excluir, fica null)
            $table->foreignId('idEnfermeiroFK')
                  ->nullable()
                  ->constrained('tbEnfermeiro', 'idEnfermeiroPK')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // FK -> tbUnidade.idUnidadePK (nullable se não for obrigatório)
            $table->foreignId('idUnidadeFK')
                  ->nullable()
                  ->constrained('tbUnidade', 'idUnidadePK')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Nome da unidade (denormalizado para histórico)
            $table->string('unidade', 255)->nullable();

            // Data da consulta
            $table->date('dataConsulta');

            // Campos principais do prontuário
            $table->text('observacoes')->nullable();
            $table->text('examesSolicitados')->nullable();
            $table->text('medicamentosPrescritos')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices auxiliares de busca
            $table->index(['idProntuarioFK', 'dataConsulta']);
            $table->index('dataConsulta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbConsulta');
    }
};