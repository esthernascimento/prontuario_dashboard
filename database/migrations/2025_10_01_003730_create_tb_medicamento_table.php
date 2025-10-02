<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbMedicamento', function (Blueprint $table) {
            $table->id('idMedicamentoPK');

            // FK -> tbConsulta.idConsultaPK (muitos medicamentos por consulta)
            $table->foreignId('idConsultaFK')
                  ->constrained('tbConsulta', 'idConsultaPK')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // FK -> tbPaciente.idPaciente (muitos medicamentos por paciente)
            $table->foreignId('idPacienteFK')
                  ->constrained('tbPaciente', 'idPaciente')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('descMedicamento');
            $table->text('posologia')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // (Opcional) Evitar duplicar o MESMO medicamento na MESMA consulta:
             $table->unique(['idConsultaFK', 'descMedicamento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbMedicamento');
    }
};
