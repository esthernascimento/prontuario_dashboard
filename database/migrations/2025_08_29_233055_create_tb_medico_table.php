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
        Schema::create('tbMedico', function (Blueprint $table) {
            $table->id('idMedicoPK');
            $table->string('nomeMedico');
            $table->string('crmMedico')->unique();
            $table->string('especialidadeMedico')->nullable(); 

       
            $table->foreignId('id_usuarioFK')
                  ->constrained('tbUsuario', 'idUsuarioPK')
                  ->onDelete('cascade');

            $table->timestamp('dataCadastroMedico')->useCurrent();
            $table->timestamp('dataAtualizacaoMedico')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbMedico');
    }
};
