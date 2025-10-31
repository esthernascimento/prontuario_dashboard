<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbAlergia', function (Blueprint $table) {
            // Novos atributos conforme MER
            $table->string('nomeAlergia')->nullable()->after('idPacienteFK');
            $table->string('tipoAlergia')->nullable()->after('nomeAlergia');
            $table->string('severidadeAlergia')->nullable()->after('tipoAlergia');
            // Mantém descAlergia existente; campos extras antigos (gravidade/reacao) continuam para compatibilidade
        });
    }

    public function down(): void
    {
        Schema::table('tbAlergia', function (Blueprint $table) {
            foreach (['severidadeAlergia','tipoAlergia','nomeAlergia'] as $col) {
                if (Schema::hasColumn('tbAlergia', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};