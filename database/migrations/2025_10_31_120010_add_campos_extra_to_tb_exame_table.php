<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbExame', function (Blueprint $table) {
            // Novos atributos conforme MER
            $table->string('nomeExame')->nullable()->after('idPacienteFK');
            $table->string('tipoExame')->nullable()->after('nomeExame');
            // 'descExame' e 'dataExame' já existem pela migration original
        });
    }

    public function down(): void
    {
        Schema::table('tbExame', function (Blueprint $table) {
            foreach (['tipoExame','nomeExame'] as $col) {
                if (Schema::hasColumn('tbExame', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};