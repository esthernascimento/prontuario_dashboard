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
        Schema::create('tbRecepcionista', function (Blueprint $table) {
            $table->id('idRecepcionistaPK');
            $table->string('nomeRecepcionista');
            $table->string('emailRecepcionista')->unique();
            $table->string('senhaRecepcionista');
            
            // Chave estrangeira para ligar ao Admin que o criou/gere 
            $table->foreignId('idUnidadeFK')->constrained('tbUnidade', 'idUnidadePK');
            
            $table->timestamps(); // Colunas created_at e updated_at
            $table->softDeletes(); // Coluna deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbRecepcionista');
    }
};
