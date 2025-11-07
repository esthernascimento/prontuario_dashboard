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
            
            // --- ADICIONA A COLUNA DO PACIENTE QUE FALTA ---
            
            // Precisamos que ela seja 'nullable' porque as consultas 
            // que os médicos já criaram (ligadas pelo prontuário) não terão esse ID.
            $table->foreignId('idPacienteFK')
                  ->nullable() 
                  ->constrained('tbPaciente', 'idPaciente') // Assumindo que a PK de tbPaciente é 'idPaciente'
                  ->after('idConsultaPK'); // Coloca no início da tabela
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbConsulta', function (Blueprint $table) {
            // Remove a chave estrangeira e a coluna
            $table->dropForeign(['idPacienteFK']);
            $table->dropColumn('idPacienteFK');
        });
    }
};