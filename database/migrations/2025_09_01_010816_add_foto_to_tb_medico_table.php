<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbmedico', function (Blueprint $table) {
            if (!Schema::hasColumn('tbmedico', 'foto')) {
                $table->string('foto')->nullable()->after('especialidadeMedico');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbmedico', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
