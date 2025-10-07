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
        Schema::table('tbConsulta', function (Blueprint $table) {
            
            $table->string('statusConsulta', 50)->default('EM_TRIAGEM')->after('obsConsulta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbConsulta', function (Blueprint $table) {
            $table->dropColumn('statusConsulta');
        });
    }
};
