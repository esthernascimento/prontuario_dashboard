<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tbMedico', function (Blueprint $table) {
            $table->dropColumn('emailMedico');
        });
    }

    public function down()
    {
        Schema::table('tbMedico', function (Blueprint $table) {
            $table->string('emailMedico')->nullable()->after('especialidadeMedico');
        });
    }
};
