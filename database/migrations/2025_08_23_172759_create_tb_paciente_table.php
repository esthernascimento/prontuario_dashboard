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
        Schema::create('tbPaciente', function (Blueprint $table) {
            $table->id('idPacientePK');
            $table->string('nomePaciente');
            $table->string('cpfPaciente', 14)->unique();
            $table->string('cartaoSusPaciente', 20)->unique();
            $table->date('dataNascPaciente');
            $table->string('logradouroPaciente')->nullable();
            $table->string('cidadePaciente', 100)->nullable();
            $table->char('ufPaciente', 2)->nullable();
            $table->string('cepPaciente', 9)->nullable();
            $table->text('alergiasPaciente')->nullable();
            
            $table->unsignedBigInteger('id_usuarioFK');
            $table->foreign('id_usuarioFK')->references('idUsuarioPK')->on('tbUsuario')->onDelete('cascade');
            
            $table->timestamp('dataCadastroPaciente')->nullable();
            $table->timestamp('dataAtualizacaoPaciente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbPaciente');
    }
};
