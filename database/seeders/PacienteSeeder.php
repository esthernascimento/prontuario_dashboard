<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('pt_BR');
        $generos = ['Masculino', 'Feminino', 'Outro'];
        $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

        // Usado para garantir a unicidade de CPF e Cartão SUS
        $usedCpfs = [];
        $usedSusCards = [];
        
        // Função para gerar um CPF único (11 dígitos)
        $generateUniqueCpf = function () use ($faker, &$usedCpfs) {
            do {
                $cpf = str_replace(['.', '-'], '', $faker->cpf(false));
            } while (in_array($cpf, $usedCpfs));
            $usedCpfs[] = $cpf;
            return $cpf;
        };

        // Função para gerar um Cartão SUS único
        $generateUniqueSusCard = function () use ($faker, &$usedSusCards) {
            do {
                $susCard = $faker->numerify('###############');
            } while (in_array($susCard, $usedSusCards));
            $usedSusCards[] = $susCard;
            return $susCard;
        };

        // LOOP ALTERADO PARA 50 INSERTS
        for ($i = 0; $i < 50; $i++) {
            $genero = $faker->randomElement($generos);
            $uf = $faker->randomElement($estados);
            
            $nome = ($genero === 'Masculino') ? $faker->firstNameMale() . ' ' . $faker->lastName() : 
                    (($genero === 'Feminino') ? $faker->firstNameFemale() . ' ' . $faker->lastName() : 
                    $faker->name());

            Paciente::create([
                'nomePaciente'          => $nome,
                'cpfPaciente'           => $generateUniqueCpf(),
                'dataNascPaciente'      => $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
                'cartaoSusPaciente'     => $generateUniqueSusCard(),
                'generoPaciente'        => $genero,
                'statusPaciente'        => $faker->boolean(90),
                'emailPaciente'         => $faker->unique()->safeEmail(),
                'senhaPaciente'         => Hash::make('password'),
                'telefonePaciente'      => $faker->cellphoneNumber(false),
                'fotoPaciente'          => 'avatars/' . Str::slug($nome) . '.jpg',
                'logradouroPaciente'    => $faker->streetName(),
                'numLogradouroPaciente' => $faker->buildingNumber(),
                'cepPaciente'           => str_replace(['.', '-'], '', $faker->postcode()),
                'bairroPaciente'        => $faker->citySuffix(),
                'cidadePaciente'        => $faker->city(),
                'ufPaciente'            => $uf,
                'estadoPaciente'        => $faker->state(),
                'paisPaciente'          => 'Brasil',
            ]);
        }
    }
}