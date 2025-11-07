<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Recepcionista;
use App\Models\Unidade;

class RecepcionistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca unidades existentes
        $unidades = Unidade::all();

        if ($unidades->isEmpty()) {
            $this->command->info('Nenhuma unidade encontrada. Criando recepcionistas com unidade padrão...');
            
            Recepcionista::create([
                'nomeRecepcionista' => 'Carlos Almeida (Recepção)',
                'emailRecepcionista' => 'recepcao@prontuario.com',
                'senhaRecepcionista' => Hash::make('senha123'),
                'idUnidadeFK' => 1, // ID padrão
            ]);

            Recepcionista::create([
                'nomeRecepcionista' => 'Ana Beatriz (Recepção Tarde)',
                'emailRecepcionista' => 'ana.recep@prontuario.com',
                'senhaRecepcionista' => Hash::make('senha123'),
                'idUnidadeFK' => 1, // ID padrão
            ]);
        } else {
            // Cria recepcionistas para cada unidade
            foreach ($unidades as $index => $unidade) {
                $recepcionistasData = [
                    [
                        'nomeRecepcionista' => 'Carlos Almeida',
                        'emailRecepcionista' => 'recepcao.unidade' . ($index + 1) . '@prontuario.com',
                        'senhaRecepcionista' => Hash::make('senha123'),
                        'idUnidadeFK' => $unidade->idUnidadePK,
                    ],
                    [
                        'nomeRecepcionista' => 'Ana Beatriz',
                        'emailRecepcionista' => 'ana.unidade' . ($index + 1) . '@prontuario.com',
                        'senhaRecepcionista' => Hash::make('senha123'),
                        'idUnidadeFK' => $unidade->idUnidadePK,
                    ],
                    [
                        'nomeRecepcionista' => 'João Silva',
                        'emailRecepcionista' => 'joao.unidade' . ($index + 1) . '@prontuario.com',
                        'senhaRecepcionista' => Hash::make('senha123'),
                        'idUnidadeFK' => $unidade->idUnidadePK,
                    ]
                ];

                foreach ($recepcionistasData as $data) {
                    Recepcionista::create($data);
                }
            }

            $this->command->info('Recepcionistas criados para ' . $unidades->count() . ' unidades.');
        }
    }
}