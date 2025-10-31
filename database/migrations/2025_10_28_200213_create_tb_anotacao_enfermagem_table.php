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
        Schema::create('tbAnotacaoEnfermagem', function (Blueprint $table) {
            $table->id('idAnotacaoPK');

            // Chave estrangeira para o Paciente
            $table->foreignId('idPacienteFK')
                  ->constrained('tbPaciente', 'idPaciente') // Confirma que a PK de tbPaciente é 'idPaciente'
                  ->cascadeOnDelete();

            // Chave estrangeira para o Enfermeiro
            $table->foreignId('idEnfermeiroFK')
                  ->constrained('tbEnfermeiro', 'idEnfermeiroPK'); // Confirma que a PK de tbEnfermeiro é 'idEnfermeiroPK'
            
            // Chave estrangeira para a Unidade (baseado no seu controller)
            $table->foreignId('unidade_atendimento') // O nome da coluna que seu controller usa
                  ->constrained('tbUnidade', 'idUnidadePK'); // A tabela/coluna que seu controller valida

            // Campos do seu formulário
            $table->string('tipo_registro');
            $table->dateTime('data_hora');
            $table->text('descricao');
            $table->string('pressao_arterial')->nullable();
            $table->string('temperatura')->nullable();
            $table->string('frequencia_cardiaca')->nullable();
            $table->string('frequencia_respiratoria')->nullable();
            $table->string('saturacao')->nullable();
            $table->integer('dor')->nullable();
            $table->text('alergias')->nullable();
            $table->text('medicacoes_ministradas')->nullable();

            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbAnotacaoEnfermagem');
    }
};
