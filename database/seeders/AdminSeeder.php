<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin; // Importa o nosso Model de Admin
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Senha padrão para todos os administradores de teste
        $senhaPadrao = Hash::make('12345678');

        // Lista de administradores a serem criados
        $admins = [
            ['nomeAdmin' => 'Hyago', 'emailAdmin' => 'hyago@adm.com'],
            ['nomeAdmin' => 'Gabriel S', 'emailAdmin' => 'gabriels@adm.com'],
            ['nomeAdmin' => 'Gabriel A', 'emailAdmin' => 'gabriela@adm.com'],
            ['nomeAdmin' => 'Esther', 'emailAdmin' => 'esther@adm.com'],
            ['nomeAdmin' => 'Kauã', 'emailAdmin' => 'kaua@adm.com'],
            ['nomeAdmin' => 'Gisele', 'emailAdmin' => 'gisele@adm.com'],
        ];

        // Cria cada admin na tabela, evitando duplicados pelo email
        foreach ($admins as $adminData) {
            Admin::updateOrCreate(
                ['emailAdmin' => $adminData['emailAdmin']], // Chave para verificar se já existe
                [
                    'nomeAdmin' => $adminData['nomeAdmin'],
                    'senhaAdmin' => $senhaPadrao,
                ]
            );
        }

        // Informa no terminal que o seeder foi executado com sucesso
        $this->command->info('Tabela de Administradores populada com sucesso!');
    }
}
