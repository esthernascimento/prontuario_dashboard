<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbProntuario', function (Blueprint $table) {
            $table->id('idProntuarioPK');

            // FK para tbPaciente.idPaciente (1:1 com unique)
            $table->foreignId('idPacienteFK')
                  ->constrained('tbPaciente', 'idPaciente')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete()
                  ->unique();

            $table->date('dataAbertura');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbProntuario');
    }
};
