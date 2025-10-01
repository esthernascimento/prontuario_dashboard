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
        Schema::create('tbMedicamento', function (Blueprint $table) {
            $table->id('idMedicamentoPK');

            // Ligação à tabela de Consulta (que deve ser criada antes)
            $table->foreignId('idConsultaFK')->constrained('tbConsulta', 'idConsultaPK');
            
            // CORREÇÃO CRUCIAL AQUI:
            // Apontando para a tabela 'pacientes' e para a sua chave primária 'id'.
            $table->foreignId('idPacienteFK')->unique()->constrained('tbPaciente', 'idPacientePK');

            $table->string('descMedicamento');
            $table->text('posologia')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbMedicamento');
    }
};

