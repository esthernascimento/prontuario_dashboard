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

            // FK -> tbMedico.idMedicoPK (histórico costuma não ser apagado ao excluir médico)
            $table->foreignId('idMedicoFK')
                  ->constrained('tbMedico', 'idMedicoPK')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // ou ->noAction()

            // FK -> tbEnfermeiro.idEnfermeiroPK (nullable; se excluir, fica null)
            $table->foreignId('idEnfermeiroFK')
                  ->nullable()
                  ->constrained('tbEnfermeiro', 'idEnfermeiroPK')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // FK -> tbUnidade.idUnidadePK
            $table->foreignId('idUnidadeFK')
                  ->constrained('tbUnidade', 'idUnidadePK')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // ajuste se quiser cascata

            $table->dateTime('dataConsulta');
            $table->text('obsConsulta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // (Opcional) Índices auxiliares de busca:
            // $table->index(['idProntuarioFK', 'dataConsulta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbConsulta');
    }
};
