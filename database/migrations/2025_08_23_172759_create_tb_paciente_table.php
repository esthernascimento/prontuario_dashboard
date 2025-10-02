<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbPaciente', function (Blueprint $table) {
            $table->id('idPaciente');

            // Dados do Paciente
            $table->string('nomePaciente');                        
            $table->string('cpfPaciente', 11)->unique();           
            $table->date('dataNascPaciente');                       
            $table->string('cartaoSusPaciente', 20)->unique();     
            $table->string('generoPaciente')->nullable();   
            $table->string('fotoPaciente')->nullable(); 
            $table->string('telefonePaciente', 20)->nullable();

            // Endereço
            $table->string('logradouroPaciente')->nullable();
            $table->string('numLogradouroPaciente')->nullable();
            $table->string('cepPaciente', 9)->nullable();
            $table->string('bairroPaciente')->nullable();
            $table->string('cidadePaciente')->nullable();
            $table->string('ufPaciente', 2)->nullable();
            $table->string('estadoPaciente')->nullable();
            $table->string('paisPaciente')->nullable();

            // Autenticação
            $table->string('emailPaciente')->unique()->nullable();
            $table->string('senhaPaciente')->nullable();        

            // Controle
            $table->boolean('statusPaciente')->default(true);       
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbPaciente');
    }
};
