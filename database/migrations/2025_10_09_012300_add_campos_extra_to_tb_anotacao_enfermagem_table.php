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
    Schema::table('tbAnotacaoEnfermagem', function (Blueprint $table) {
        $table->integer('frequencia_respiratoria')->nullable();
        $table->integer('dor')->nullable();
        $table->text('alergias')->nullable();
        $table->text('medicacoes_ministradas')->nullable();
    });
}

public function down(): void
{
    Schema::table('tbAnotacaoEnfermagem', function (Blueprint $table) {
        $table->dropColumn([
            'frequencia_respiratoria',
            'dor',
            'alergias',
            'medicacoes_ministradas',
        ]);
    });
}

};
