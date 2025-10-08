<?php

namespace Database\Seeders;

use App\Models\Medico;
use App\Models\Usuario;
use App\Models\Unidade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class MedicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('pt_BR');
        
        $unidades = Unidade::all();
        if ($unidades->isEmpty()) {
            $this->command->info('Criando unidades de saúde para associar aos médicos...');
            $unidades = [];
            for ($i = 0; $i < 5; $i++) {
                $unidades[] = Unidade::create([
                    'nomeUnidade' => $faker->company . ' - Unidade ' . ($i + 1),
                    'tipoUnidade' => $faker->randomElement(['Hospital', 'Clínica', 'Posto de Saúde']),
                    'logradouroUnidade' => $faker->streetName,
                    'numLogradouroUnidade' => $faker->buildingNumber,
                    'cidadeUnidade' => $faker->city,
                    'ufUnidade' => $faker->stateAbbr,
                ]);
            }
        }
        $unidadeIds = collect($unidades)->pluck('idUnidadePK')->toArray();

        $usedCrms = [];

        $generateUniqueCrm = function () use ($faker, &$usedCrms) {
            do {
                $crm = $faker->numerify('#####') . '/' . $faker->stateAbbr;
            } while (in_array($crm, $usedCrms));
            $usedCrms[] = $crm;
            return $crm;
        };

        // LOOP ALTERADO PARA 50 INSERTS
        for ($i = 0; $i < 50; $i++) {
            $nomeCompleto = $faker->name();
            $email = $faker->unique()->safeEmail();

            $usuario = Usuario::create([
                'nomeUsuario'       => $nomeCompleto,
                'emailUsuario'      => $email,
                'senhaUsuario'      => Hash::make('password'),
                'statusAtivoUsuario' => $faker->boolean(90),
                'statusSenhaUsuario' => true,
            ]);

            $medico = Medico::create([
                'id_usuarioFK'          => $usuario->idUsuarioPK,
                'nomeMedico'            => $nomeCompleto,
                'crmMedico'             => $generateUniqueCrm(),
                'especialidadeMedico'   => $faker->randomElement(['Clínico Geral', 'Pediatra', 'Cardiologista', 'Dermatologista', 'Ortopedista', 'Ginecologista']),
            ]);

            $unidadesAleatorias = $faker->randomElements($unidadeIds, $faker->numberBetween(1, 2));
            $medico->unidades()->sync($unidadesAleatorias);
        }
    }
}