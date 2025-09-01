<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbUsuario', function (Blueprint $table) {
    if (!Schema::hasColumn('tbUsuario', 'foto')) {
        $table->string('foto')->nullable()->after('statusAtivoUsuario');
    }
});

    }

    public function down(): void
    {
        Schema::table('tbusuario', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
