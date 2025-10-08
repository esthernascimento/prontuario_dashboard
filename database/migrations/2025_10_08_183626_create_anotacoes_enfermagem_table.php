<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbAnotacaoEnfermagem', function (Blueprint $table) {
            $table->id('idAnotacao');

            $table->foreignId('idPacienteFK')->constrained('tbPaciente', 'idPaciente');
            
            // 2. Chave para Enfermeiro: assume que tbEnfermeiro tem 'idEnfermeiro'
            // O 'constrained' garante que o tipo (unsignedBigInteger) e a referência estejam corretos.
            $table->foreignId('idEnfermeiroFK')->constrained('tbEnfermeiro', 'idEnfermeiro'); 

            $table->dateTime('data_hora');
            $table->string('tipo_registro', 50);
            $table->string('unidade_atendimento', 255);
            $table->text('descricao');
            
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