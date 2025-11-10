<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

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

        $usedCpfs = [];
        $usedSusCards = [];
        
        $generateUniqueCpf = function () use ($faker, &$usedCpfs) {
            do {
                $cpf = str_replace(['.', '-'], '', $faker->cpf(false));
            } while (in_array($cpf, $usedCpfs));
            $usedCpfs[] = $cpf;
            return $cpf;
        };

        $generateUniqueSusCard = function () use ($faker, &$usedSusCards) {
            do {
                $susCard = $faker->numerify('###############');
            } while (in_array($susCard, $usedSusCards));
            $usedSusCards[] = $susCard;
            return $susCard;
        };


        $testCpf = '81773179896';
        $testSus = '420588032068787';
        $testNome = 'Tomás Delgado';

        $usedCpfs[] = $testCpf;
        $usedSusCards[] = $testSus;

        Paciente::create([
            'nomePaciente'          => $testNome,
            'cpfPaciente'           => $testCpf,
            'dataNascPaciente'      => '1981-08-27',
            'cartaoSusPaciente'     => $testSus,
            'generoPaciente'        => 'Masculino',
            'statusPaciente'        => true,
            'emailPaciente'         => 'pablo.sandoval@example.org',
            'senhaPaciente'         => Hash::make('senha123'), // <-- Senha correta
            'telefonePaciente'      => '13941737807',
            'fotoPaciente'          => 'avatars/tomas-delgado.jpg',
            'logradouroPaciente'    => 'Av. Natan Soares',
            'numLogradouroPaciente' => '70',
            'cepPaciente'           => '51737114',
            'bairroPaciente'        => 'do Leste',
            'cidadePaciente'        => 'Santiago do Norte',
            'ufPaciente'            => 'BA',
            'estadoPaciente'        => 'Paraná',
            'paisPaciente'          => 'Brasil',
            'created_at'            => '2025-09-23 21:00:29',
            'updated_at'            => '2025-09-23 21:00:29',
        ]);

  
        $startDate = Carbon::create(2025, 5, 1);
        $endDate = Carbon::now();

        for ($i = 0; $i < 99; $i++) { // <-- CORRIGIDO: de 100 para 99
            $genero = $faker->randomElement($generos);
            $uf = $faker->randomElement($estados);
            
            $nome = ($genero === 'Masculino') ? $faker->firstNameMale() . ' ' . $faker->lastName() : 
                    (($genero === 'Feminino') ? $faker->firstNameFemale() . ' ' . $faker->lastName() : 
                    $faker->name());

            $createdAt = $faker->dateTimeBetween($startDate, $endDate);
            
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
                'created_at'            => $createdAt,
                'updated_at'            => $createdAt,
            ]);
        }
    }
}