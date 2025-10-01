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
        Schema::create('tbExame', function (Blueprint $table) {
            $table->id('idExamePK');
            $table->foreignId('idConsultaFK')->constrained('tbConsulta', 'idConsultaPK');
            $table->foreignId('idPacienteFK')->unique()->constrained('tbPaciente', 'idPacientePK');
            $table->string('descExame');
            $table->text('resultadoExame')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbExame');
    }
};

