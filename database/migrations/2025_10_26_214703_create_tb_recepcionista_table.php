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
            
            // --- CORREÇÃO (Voltando à sua lógica correta) ---
            // Chave estrangeira para ligar à Unidade que o cadastrou
            $table->foreignId('idUnidadeFK')
                  ->nullable() // Deixei nulo por segurança, mas pode ser obrigatório
                  ->constrained('tbUnidade', 'idUnidadePK') // Assumindo PK da tbUnidade
                  ->nullOnDelete(); // Se a unidade for deletada, o recepcionista fica "sem unidade"
            
            $table->timestamps(); 
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Método robusto para apagar
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tbRecepcionista');
        Schema::enableForeignKeyConstraints();
    }
};