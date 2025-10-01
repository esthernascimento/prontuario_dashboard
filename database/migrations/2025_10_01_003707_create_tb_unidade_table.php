<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbUnidade', function (Blueprint $table) {
            $table->id('idUnidadePK');
            $table->string('nomeUnidade');
            $table->string('tipoUnidade', 100)->nullable();
            $table->string('enderecoUnidade')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbUnidade');
    }
};
