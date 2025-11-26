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
            
            // --- LINHA ADICIONADA ---
            $table->boolean('statusAtivoRecepcionista')->default(1);
            // --- FIM DA LINHA ADICIONADA ---
            
            // Chave estrangeira para ligar à Unidade que o cadastrou
            $table->foreignId('idUnidadeFK')
                  ->nullable() 
                  ->constrained('tbUnidade', 'idUnidadePK')
                  ->nullOnDelete();
            
            $table->timestamps(); 
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tbRecepcionista');
        Schema::enableForeignKeyConstraints();
    }
};