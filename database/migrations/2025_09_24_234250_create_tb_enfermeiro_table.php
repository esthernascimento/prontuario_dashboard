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
        Schema::create('tbEnfermeiro', function (Blueprint $table) {
            $table->bigIncrements('idEnfermeiroPK');
            $table->string('nomeEnfermeiro', 255);
            $table->string('emailEnfermeiro', 255)->unique();
            $table->string('corenEnfermeiro', 50)->unique();
            $table->string('especialidadeEnfermeiro', 100)->nullable();

            // FK para usuário
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')
                  ->references('idUsuarioPK')
                  ->on('tbUsuario')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbEnfermeiro');
    }
};
