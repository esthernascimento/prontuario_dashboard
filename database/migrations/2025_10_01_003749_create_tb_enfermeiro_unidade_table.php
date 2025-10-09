<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbEnfermeiroUnidade', function (Blueprint $table) {
            $table->id('idEnfermeiroUnidadePK');

            // Chaves estrangeiras
            $table->unsignedBigInteger('idEnfermeiroFK');
            $table->unsignedBigInteger('idUnidadeFK');

            // Relacionamentos
            $table->foreign('idEnfermeiroFK')->references('idEnfermeiroPK')->on('tbEnfermeiro')->onDelete('cascade');
            $table->foreign('idUnidadeFK')->references('idUnidadePK')->on('tbUnidade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbEnfermeiroUnidade');
    }
};
