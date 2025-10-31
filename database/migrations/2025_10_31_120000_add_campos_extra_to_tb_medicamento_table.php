<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbMedicamento', function (Blueprint $table) {
            // Novos atributos conforme MER
            $table->string('tipoMedicamento')->nullable()->after('descMedicamento');
            $table->string('nomeMedicamento')->nullable()->after('tipoMedicamento');
            $table->string('dosagemMedicamento')->nullable()->after('nomeMedicamento');
            $table->string('frequenciaMedicamento')->nullable()->after('dosagemMedicamento');
            $table->string('periodoMedicamento')->nullable()->after('frequenciaMedicamento');

            // Vincular também ao prontuário (além da consulta), mantendo compatibilidade
            if (!Schema::hasColumn('tbMedicamento', 'idProntuarioFK')) {
                $table->foreignId('idProntuarioFK')
                      ->nullable()
                      ->constrained('tbProntuario', 'idProntuarioPK')
                      ->cascadeOnUpdate()
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbMedicamento', function (Blueprint $table) {
            // Remoção segura dos campos
            foreach (['periodoMedicamento','frequenciaMedicamento','dosagemMedicamento','nomeMedicamento','tipoMedicamento'] as $col) {
                if (Schema::hasColumn('tbMedicamento', $col)) {
                    $table->dropColumn($col);
                }
            }
            if (Schema::hasColumn('tbMedicamento', 'idProntuarioFK')) {
                $table->dropConstrainedForeignId('idProntuarioFK');
            }
        });
    }
};