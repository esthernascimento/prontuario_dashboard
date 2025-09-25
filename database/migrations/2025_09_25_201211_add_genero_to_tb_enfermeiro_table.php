<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('tbEnfermeiro', function (Blueprint $table) {
        $table->string('genero', 20)->after('especialidadeEnfermeiro')->nullable();
    });
}

public function down()
{
    Schema::table('tbEnfermeiro', function (Blueprint $table) {
        $table->dropColumn('genero');
    });
}

};
