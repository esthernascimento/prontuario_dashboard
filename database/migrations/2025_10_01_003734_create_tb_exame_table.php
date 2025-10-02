<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbExame', function (Blueprint $table) {
            $table->id('idExamePK');

            // Muitos exames por consulta
            $table->foreignId('idConsultaFK')
                  ->constrained('tbConsulta', 'idConsultaPK')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // Muitos exames por paciente
            $table->foreignId('idPacienteFK')
                  ->constrained('tbPaciente', 'idPaciente')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('descExame');
            $table->text('resultadoExame')->nullable();

            // NOVO: data do exame
            $table->date('dataExame');
            $table->index('dataExame');

            $table->timestamps();
            $table->softDeletes();

            // (Opcional) evitar duplicar MESMO exame na MESMA consulta:
             $table->unique(['idConsultaFK', 'descExame', 'dataExame']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbExame');
    }
};
