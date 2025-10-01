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
        Schema::create('tbConsulta', function (Blueprint $table) {
            $table->id('idConsultaPK');
            $table->foreignId('idProntuarioFK')->constrained('tbProntuario', 'idProntuarioPK');
            $table->foreignId('idMedicoFK')->constrained('tbMedico', 'idMedicoPK');
            $table->foreignId('idEnfermeiroFK')->nullable()->constrained('tbEnfermeiro', 'idEnfermeiroPK');
            $table->foreignId('idUnidadeFK')->constrained('tbUnidade', 'idUnidadePK');
            $table->dateTime('dataConsulta');
            $table->text('obsConsulta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbConsulta');
    }
};

