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
        Schema::create('tbEnfermeiroUnidade', function (Blueprint $table) {
            $table->id('idEnfermeiroUnidadePK');
            $table->foreignId('idEnfermeiroFK')->constrained('tbEnfermeiro', 'idEnfermeiroPK');
            $table->foreignId('idUnidadeFK')->constrained('tbUnidade', 'idUnidadePK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbEnfermeiroUnidade');
    }
};

