<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Recepcionista;
use App\Models\Unidade;

class RecepcionistaSeeder extends Seeder
{
    public function run(): void
    {
        // Pega a primeira Unidade (ID 1, "Hospital Municipal Central")
        $unidade = Unidade::first(); 

        if (!$unidade) {
            $this->command->error('Nenhuma unidade encontrada. Rode o UnidadeSeeder primeiro.');
            return;
        }

        // --- Recepcionista Fixo (ID 1) ---
        Recepcionista::create([
            'nomeRecepcionista' => 'Carlos Almeida (Recepção)',
            'emailRecepcionista' => 'recepcao@prontuario.com',
            'senhaRecepcionista' => Hash::make('senha123'),
            'idUnidadeFK' => $unidade->idUnidadePK, // <-- CORRIGIDO
        ]);

        // --- Recepcionista 2 (para a mesma unidade) ---
         Recepcionista::create([
            'nomeRecepcionista' => 'Ana Beatriz (Recepção Tarde)',
            'emailRecepcionista' => 'ana.recep@prontuario.com',
            'senhaRecepcionista' => Hash::make('senha123'),
            'idUnidadeFK' => $unidade->idUnidadePK, // <-- CORRIGIDO
        ]);

        $this->command->info('Recepcionistas fixos criados e associados à Unidade 1.');
    }
}