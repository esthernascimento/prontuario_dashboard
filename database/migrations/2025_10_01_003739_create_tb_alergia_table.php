<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbAlergia', function (Blueprint $table) {
            $table->id('idAlergiaPK');

            // FK -> tbPaciente.idPaciente (sem unique para permitir N alergias por paciente)
            $table->foreignId('idPacienteFK')
                  ->constrained('tbPaciente', 'idPaciente')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('descAlergia'); // ex.: "Dipirona", "Amendoim" etc.
            // (Opcional) niveis adicionais:
             $table->string('gravidade')->nullable();  // leve | moderada | severa
             $table->string('reacao')->nullable();     // ex.: urticária, anafilaxia, etc.

            $table->timestamps();
            $table->softDeletes();

            // (Opcional) Evitar duplicar a MESMA alergia para o MESMO paciente:
             $table->unique(['idPacienteFK', 'descAlergia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbAlergia');
    }
};
