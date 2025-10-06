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
        Schema::create('tbUsuario', function (Blueprint $table) {
            $table->id('idUsuarioPK');
            $table->string('emailUsuario')->unique();
            $table->string('senhaUsuario');
            $table->boolean('statusAtivoUsuario')->default(true);
            $table->boolean('statusSenhaUsuario')->default(true);
            $table->timestamp('dataCadastroUsuario')->nullable();
            $table->timestamp('dataAtualizacaoUsuario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbUsuario');
    }
};
