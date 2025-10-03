<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbAlergia', function (Blueprint $table) {
            $table->id('idAlergiaPK');

         
            $table->foreignId('idPacienteFK')
                  ->constrained('tbPaciente', 'idPaciente')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('descAlergia'); 
      
             $table->string('gravidade')->nullable();  
             $table->string('reacao')->nullable();     

            $table->timestamps();
            $table->softDeletes();

             $table->unique(['idPacienteFK', 'descAlergia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbAlergia');
    }
};
