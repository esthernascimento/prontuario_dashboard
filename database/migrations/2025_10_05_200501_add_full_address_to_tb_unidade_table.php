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
        Schema::table('tbUnidade', function (Blueprint $table) {
            // Remove a coluna de endereço antiga e genérica
            if (Schema::hasColumn('tbUnidade', 'enderecoUnidade')) {
                $table->dropColumn('enderecoUnidade');
            }

            // Adiciona os novos campos de endereço e telefone
            $table->string('telefoneUnidade', 20)->nullable()->after('tipoUnidade');
            $table->string('logradouroUnidade')->nullable()->after('telefoneUnidade');
            $table->string('numLogradouroUnidade', 20)->nullable()->after('logradouroUnidade');
            $table->string('bairroUnidade', 100)->nullable()->after('numLogradouroUnidade');
            $table->string('cepUnidade', 9)->nullable()->after('bairroUnidade');
            $table->string('cidadeUnidade', 100)->nullable()->after('cepUnidade');
            $table->char('ufUnidade', 2)->nullable()->after('cidadeUnidade');
            $table->string('estadoUnidade', 100)->nullable()->after('ufUnidade');
            $table->string('paisUnidade', 100)->nullable()->after('estadoUnidade');
                $table->string('foto')->nullable()->after('enderecoUnidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbUnidade', function (Blueprint $table) {
            $table->string('enderecoUnidade')->nullable();
            $table->dropColumn([
                'telefoneUnidade',
                'logradouroUnidade',
                'numLogradouroUnidade',
                'bairroUnidade',
                'cepUnidade',
                'cidadeUnidade',
                'ufUnidade',
                'estadoUnidade',
                'paisUnidade',
            ]);
        });
    }
};
