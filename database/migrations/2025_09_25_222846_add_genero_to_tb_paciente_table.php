<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbPaciente', function (Blueprint $table) {
            if (!Schema::hasColumn('tbPaciente', 'genero')) {
                $table->string('genero', 20)->nullable()->after('nomePaciente');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbPaciente', function (Blueprint $table) {
            if (Schema::hasColumn('tbPaciente', 'genero')) {
                $table->dropColumn('genero');
            }
        });
    }
};
