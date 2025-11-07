<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Esta é a tabela-pivô que liga Recepcionistas e Unidades
        Schema::create('tbRecepcionistaUnidade', function (Blueprint $table) {
            
            // Chave estrangeira para o Recepcionista
            $table->foreignId('idRecepcionistaFK')
                  ->constrained('tbRecepcionista', 'idRecepcionistaPK') // Assumindo 'idRecepcionistaPK'
                  ->cascadeOnDelete(); // Se deletar o recepcionista, remove a ligação

            // Chave estrangeira para a Unidade
            $table->foreignId('idUnidadeFK')
                  ->constrained('tbUnidade', 'idUnidadePK') // Assumindo 'idUnidadePK'
                  ->cascadeOnDelete(); // Se deletar a unidade, remove a ligação

            // Define que a combinação (recepcionista + unidade) deve ser única
            // Isso impede que o mesmo recepcionista seja associado à mesma unidade duas vezes
            $table->primary(['idRecepcionistaFK', 'idUnidadeFK']);

            // Timestamps são opcionais para tabelas pivô, mas podem ser úteis
            // $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Método robusto para apagar
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tbRecepcionistaUnidade');
        Schema::enableForeignKeyConstraints();
    }
};