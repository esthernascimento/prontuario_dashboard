<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tbAlergia', function (Blueprint $table) {
            $table->id('idAlergiaPK');

            // FK -> tbPaciente.idPaciente
            $table->foreignId('idPacienteFK')
                  ->constrained('tbPaciente', 'idPaciente') // Confirma que a PK de tbPaciente é 'idPaciente'
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // --- CAMPOS UNIFICADOS ---

            // Campo Principal (Nome/Descrição da Alergia)
            // (O controller já está configurado para usar este campo)
            $table->string('descAlergia'); // ex.: "Dipirona", "Amendoim" etc.

            // Campos extras da sua segunda migration
            $table->string('tipoAlergia')->nullable();      // ex.: Medicamentosa, Alimentar
            $table->string('severidadeAlergia')->nullable(); // ex.: Leve, Moderada, Severa
            
            // --- FIM DOS CAMPOS UNIFICADOS ---

            $table->timestamps();
            $table->softDeletes();

            // Evitar duplicar a MESMA alergia para o MESMO paciente
            $table->unique(['idPacienteFK', 'descAlergia']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Método robusto para apagar a tabela
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tbAlergia');
        Schema::enableForeignKeyConstraints();
    }
};

