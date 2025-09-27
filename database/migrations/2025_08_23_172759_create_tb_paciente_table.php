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
        // Alterado de 'pacientes' para 'tbPaciente' para consistência
        Schema::create('tbPaciente', function (Blueprint $t) {
            $t->id();

            // Dados básicos
            $t->string('nome');
            $t->string('cpf', 14)->unique();
            $t->date('data_nasc')->nullable();
            $t->string('cartao_sus', 20)->nullable()->unique();
            $t->string('nacionalidade')->nullable();
            $t->string('genero')->nullable();
            $t->string('caminho_foto')->nullable();
            $t->string('telefone', 20)->nullable();

            // Endereço
            $t->string('logradouro')->nullable();
            $t->string('numero')->nullable();
            $t->string('cep', 9)->nullable();
            $t->string('bairro')->nullable();
            $t->string('cidade')->nullable();
            $t->string('uf', 2)->nullable();
            $t->string('estado')->nullable();
            $t->string('pais')->nullable();

            // Autenticação
            $t->string('email')->unique();
            $t->string('senha');

            $t->softDeletes();
            $t->timestamps();
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

