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
        Schema::create('tbMedicoUnidade', function (Blueprint $table) {
            $table->id('idMedicoUnidadePK');
            $table->foreignId('idMedicoFK')->constrained('tbMedico', 'idMedicoPK');
            $table->foreignId('idUnidadeFK')->constrained('tbUnidade', 'idUnidadePK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbMedicoUnidade');
    }
};

