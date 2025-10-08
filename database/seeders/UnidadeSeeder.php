<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unidade; // Importa o nosso Model de Unidade
use Illuminate\Support\Facades\DB;

class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desativa a verificação de chaves estrangeiras para limpar a tabela
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Limpa a tabela antes de a popular para evitar duplicados
        Unidade::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Lista de unidades de saúde de exemplo
        $unidades = [
            [
                'nomeUnidade' => 'Hospital Municipal Central',
                'tipoUnidade' => 'Hospital Geral',
                'logradouroUnidade' => 'Avenida Principal',
                'numLogradouroUnidade' => '1000',
                'bairroUnidade' => 'Centro',
                'cidadeUnidade' => 'São Paulo',
                'ufUnidade' => 'SP',
                'cepUnidade' => '01001-000',
            ],
            [
                'nomeUnidade' => 'UBS Vila Esperança',
                'tipoUnidade' => 'Unidade Básica de Saúde',
                'logradouroUnidade' => 'Rua das Flores',
                'numLogradouroUnidade' => '520',
                'bairroUnidade' => 'Vila Esperança',
                'cidadeUnidade' => 'São Paulo',
                'ufUnidade' => 'SP',
                'cepUnidade' => '03645-000',
            ],
            [
                'nomeUnidade' => 'Clínica Cardiológica Coração Forte',
                'tipoUnidade' => 'Clínica Especializada',
                'logradouroUnidade' => 'Rua do Coração',
                'numLogradouroUnidade' => '150',
                'bairroUnidade' => 'Jardins',
                'cidadeUnidade' => 'São Paulo',
                'ufUnidade' => 'SP',
                'cepUnidade' => '01415-000',
            ],
            [
                'nomeUnidade' => 'Centro de Diagnóstico Imagem Clara',
                'tipoUnidade' => 'Laboratório',
                'logradouroUnidade' => 'Avenida do Diagnóstico',
                'numLogradouroUnidade' => '800',
                'bairroUnidade' => 'Saúde',
                'cidadeUnidade' => 'São Paulo',
                'ufUnidade' => 'SP',
                'cepUnidade' => '04301-000',
            ],
            [
                'nomeUnidade' => 'Posto de Saúde Jardim das Oliveiras',
                'tipoUnidade' => 'Posto de Saúde',
                'logradouroUnidade' => 'Travessa das Oliveiras',
                'numLogradouroUnidade' => '30',
                'bairroUnidade' => 'Jardim das Oliveiras',
                'cidadeUnidade' => 'Guarulhos',
                'ufUnidade' => 'SP',
                'cepUnidade' => '07272-123',
            ],
        ];

        // Insere os dados na tabela
        foreach ($unidades as $unidade) {
            Unidade::create($unidade);
        }

        // Informa no terminal que o seeder foi executado com sucesso
        $this->command->info('Tabela de Unidades populada com sucesso!');
    }
}
    