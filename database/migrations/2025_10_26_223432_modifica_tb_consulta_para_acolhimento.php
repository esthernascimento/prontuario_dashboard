<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbConsulta', function (Blueprint $table) {
            
            // === 1. ADICIONANDO NOVOS CAMPOS PARA O ACOLHIMENTO ===

            // Campo para a queixa principal (o que o paciente sente)
            $table->text('queixa_principal')->nullable()->after('idConsultaPK');

            // Campo para a classificação de risco (a cor)
            $table->string('classificacao_risco', 20)->nullable()->after('queixa_principal');

            // O CAMPO MAIS IMPORTANTE: O status do fluxo
            $table->string('status_atendimento', 50)->default('AGUARDANDO_TRIAGEM')->after('classificacao_risco');
            
            // Chave estrangeira para o Recepcionista que abriu o atendimento
            // (Usando a PK da sua migration 'tbRecepcionista' que era 'idRecepcionistaPK')
            $table->foreignId('idRecepcionistaFK')
                  ->nullable()
                  ->after('idEnfermeiroFK') // Colocando perto de outras FKs
                  ->constrained('tbRecepcionista', 'idRecepcionistaPK') 
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // Se o recepcionista for deletado, o histórico não se perde

            // === 2. MODIFICANDO COLUNAS EXISTENTES ===

            // O recepcionista NÃO sabe o médico. A FK do médico DEVE ser nula no início.
            $table->foreignId('idMedicoFK')->nullable()->change();

            // Os dados denormalizados do médico também devem ser nulos no início
            $table->string('nomeMedico', 255)->nullable()->change();
            $table->string('crmMedico', 50)->nullable()->change();

            // Precisamos da HORA da consulta, não apenas da data
            $table->datetime('dataConsulta')->nullable()->change(); // Muda de DATE para DATETIME
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbConsulta', function (Blueprint $table) {
            $table->dropColumn('queixa_principal');
            $table->dropColumn('classificacao_risco');
            $table->dropColumn('status_atendimento');
            
            // Remove a FK e a coluna
            $table->dropForeign(['idRecepcionistaFK']);
            $table->dropColumn('idRecepcionistaFK');

            // Reverte as colunas para o estado original (NÃO nulas e tipo DATE)
            // (Assumindo que eram NOT NULL antes, o que é o padrão do Laravel sem ->nullable())
            $table->foreignId('idMedicoFK')->nullable(false)->change();
            $table->string('nomeMedico', 255)->nullable(false)->change();
            $table->string('crmMedico', 50)->nullable(false)->change();
            $table->date('dataConsulta')->nullable(false)->change(); // Reverte para DATE
        });
    }
};
