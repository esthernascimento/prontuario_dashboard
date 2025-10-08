<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;

class UnidadeSeeder extends Seeder
{
    /**
     * Popula a tabela tbUnidade com unidades de exemplo, garantindo distribuição regional.
     */
    public function run(): void
    {
        // Desativa a verificação de chaves estrangeiras para limpar a tabela
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Limpa a tabela antes de a popular para evitar duplicados
        Unidade::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Lista de unidades de saúde de exemplo, cobrindo todas as regiões
        $unidades = [
            // EXEMPLOS DA REGIÃO SUDESTE (SP, RJ, MG, ES)
            [
                'nomeUnidade' => 'Hospital Municipal Central',
                'tipoUnidade' => 'Hospital Geral',
                'logradouroUnidade' => 'Avenida Principal',
                'numLogradouroUnidade' => '1000',
                'bairroUnidade' => 'Centro',
                'cidadeUnidade' => 'São Paulo',
                'ufUnidade' => 'SP',
                'estadoUnidade' => 'São Paulo',
                'cepUnidade' => '01001-000',
                'paisUnidade' => 'Brasil',
            ],
            [
                'nomeUnidade' => 'UBS Vila Esperança',
                'tipoUnidade' => 'Unidade Básica de Saúde',
                'logradouroUnidade' => 'Rua das Flores',
                'numLogradouroUnidade' => '520',
                'bairroUnidade' => 'Vila Esperança',
                'cidadeUnidade' => 'São Paulo',
                'ufUnidade' => 'SP',
                'estadoUnidade' => 'São Paulo',
                'cepUnidade' => '03645-000',
                'paisUnidade' => 'Brasil',
            ],
            [
                'nomeUnidade' => 'Posto de Saúde Jardim das Oliveiras',
                'tipoUnidade' => 'Posto de Saúde',
                'logradouroUnidade' => 'Travessa das Oliveiras',
                'numLogradouroUnidade' => '30',
                'bairroUnidade' => 'Jardim das Oliveiras',
                'cidadeUnidade' => 'Guarulhos',
                'ufUnidade' => 'SP',
                'estadoUnidade' => 'São Paulo',
                'cepUnidade' => '07272-123',
                'paisUnidade' => 'Brasil',
            ],
            [
                'nomeUnidade' => 'Hospital Estadual Alberto Chaves',
                'tipoUnidade' => 'Hospital',
                'logradouroUnidade' => 'Rua da Saúde',
                'numLogradouroUnidade' => '450',
                'bairroUnidade' => 'Saúde',
                'cidadeUnidade' => 'Rio de Janeiro',
                'ufUnidade' => 'RJ',
                'estadoUnidade' => 'Rio de Janeiro',
                'cepUnidade' => '20081-000',
                'paisUnidade' => 'Brasil',
            ],

            // EXEMPLOS DA REGIÃO NORDESTE (BA, PE, CE, etc.)
            [
                'nomeUnidade' => 'UBS Centro de Saúde Ceará',
                'tipoUnidade' => 'Unidade Básica de Saúde',
                'logradouroUnidade' => 'Avenida Litorânea',
                'numLogradouroUnidade' => '123',
                'bairroUnidade' => 'Meireles',
                'cidadeUnidade' => 'Fortaleza',
                'ufUnidade' => 'CE',
                'estadoUnidade' => 'Ceará',
                'cepUnidade' => '60165-080',
                'paisUnidade' => 'Brasil',
            ],
            
            // EXEMPLOS DA REGIÃO SUL (PR, SC, RS)
            [
                'nomeUnidade' => 'Unidade de Pronto Atendimento Sul',
                'tipoUnidade' => 'UPA',
                'logradouroUnidade' => 'Rua das Araucárias',
                'numLogradouroUnidade' => '789',
                'bairroUnidade' => 'Pinheirinho',
                'cidadeUnidade' => 'Curitiba',
                'ufUnidade' => 'PR',
                'estadoUnidade' => 'Paraná',
                'cepUnidade' => '81880-000',
                'paisUnidade' => 'Brasil',
            ],

            // EXEMPLOS DA REGIÃO CENTRO-OESTE (DF, GO, MT, MS)
            [
                'nomeUnidade' => 'UBS Planalto Central',
                'tipoUnidade' => 'Unidade Básica de Saúde',
                'logradouroUnidade' => 'Eixo Monumental',
                'numLogradouroUnidade' => '10',
                'bairroUnidade' => 'Asa Norte',
                'cidadeUnidade' => 'Brasília',
                'ufUnidade' => 'DF',
                'estadoUnidade' => 'Distrito Federal',
                'cepUnidade' => '70070-700',
                'paisUnidade' => 'Brasil',
            ],
            
            // EXEMPLOS DA REGIÃO NORTE (AM, PA, TO, etc.)
            [
                'nomeUnidade' => 'Hospital do Rio Amazonas',
                'tipoUnidade' => 'Hospital',
                'logradouroUnidade' => 'Avenida Amazonas',
                'numLogradouroUnidade' => '500',
                'bairroUnidade' => 'Centro',
                'cidadeUnidade' => 'Manaus',
                'ufUnidade' => 'AM',
                'estadoUnidade' => 'Amazonas',
                'cepUnidade' => '69010-001',
                'paisUnidade' => 'Brasil',
            ],
        ];

        // Insere os dados na tabela
        foreach ($unidades as $unidade) {
            // Certifica-se de que os campos opcionais têm um valor padrão se não estiverem na lista de teste.
            Unidade::create(array_merge([
                'telefoneUnidade' => null,
                'estadoUnidade' => null,
                'paisUnidade' => 'Brasil',
            ], $unidade));
        }

        $this->command->info('Tabela de Unidades populada com sucesso, cobrindo todas as 5 regiões!');
    }
}