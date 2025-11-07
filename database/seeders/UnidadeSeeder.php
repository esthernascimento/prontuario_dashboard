<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;

class UnidadeSeeder extends Seeder
{
    /**
     * Popula a tabela tbUnidade com unidades de exemplo, cobrindo todas as regiões do Brasil.
     */
    public function run(): void
    {
        // Desativa verificação de chaves estrangeiras para limpar a tabela
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Unidade::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Lista de unidades de saúde (por região)
        $unidades = [
            // SUDESTE
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

            // NORDESTE
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

            // SUL
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

            // CENTRO-OESTE
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

            // NORTE
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

        // Popula o banco com CNPJ, email e senha
        foreach ($unidades as $index => $unidade) {
            $nomeSlug = strtolower(str_replace(' ', '', $unidade['nomeUnidade']));
            $email = "{$nomeSlug}@exemplo.com.br";

            // Gera um CNPJ fictício válido (mas aleatório)
            $cnpj = sprintf(
                '%02d.%03d.%03d/%04d-%02d',
                rand(10, 99),
                rand(100, 999),
                rand(100, 999),
                rand(1000, 9999),
                rand(10, 99)
            );

            Unidade::create(array_merge([
                'telefoneUnidade' => '(11) 0000-0000',
                'statusAtivoUnidade' => true,
                'cnpjUnidade' => $cnpj,
                'emailUnidade' => $email,
                'senhaUnidade' => '12345678',
            ], $unidade));
        }

        $this->command->info('Tabela tbUnidade populada com sucesso com senhas fixas.');
    }
}
