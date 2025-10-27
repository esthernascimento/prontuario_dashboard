<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Recepcionista; // Importe seu model
use App\Models\Admin; // Importe o model Admin

class RecepcionistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // IMPORTANTE: Garanta que você tenha um Admin com ID 1
        // ou troque o '1' por um ID de Admin que exista no seu banco.
        $adminId = Admin::first()->idAdminPK ?? 1;

        // Limpa a tabela antes de popular (opcional, mas bom para testes)
        // Recepcionista::truncate(); // Cuidado se tiver chaves estrangeiras

        Recepcionista::create([
            'nomeRecepcionista' => 'Carlos Almeida (Recepção)',
            'emailRecepcionista' => 'recepcao@prontuario.com',
            'senhaRecepcionista' => Hash::make('senha123'), // Senha padrão
            'idAdminFK' => $adminId, 
        ]);

        Recepcionista::create([
            'nomeRecepcionista' => 'Ana Beatriz (Recepção Tarde)',
            'emailRecepcionista' => 'ana.recep@prontuario.com',
            'senhaRecepcionista' => Hash::make('senha123'),
            'idAdminFK' => $adminId,
        ]);
    }
}