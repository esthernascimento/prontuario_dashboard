<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use Database\Seeders\AdminSeeder; 
use Database\Seeders\UnidadeSeeder;
use Database\Seeders\PacienteSeeder;
use Database\Seeders\RecepcionistaSeeder;
use Database\Seeders\EnfermeiroSeeder;
use Database\Seeders\MedicoSeeder;
use Database\Seeders\ConsultasTestSeeder; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        $this->call([
            
     
            AdminSeeder::class,    
            UnidadeSeeder::class,
            PacienteSeeder::class,
            
            RecepcionistaSeeder::class,
            MedicoSeeder::class,
            EnfermeiroSeeder::class,
       
            ConsultasTestSeeder::class,
        ]);
    }
}