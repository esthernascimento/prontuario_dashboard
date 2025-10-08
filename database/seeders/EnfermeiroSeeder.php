<?php

namespace Database\Seeders;

use App\Models\Enfermeiro;
use App\Models\Usuario;
use App\Models\Unidade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class EnfermeiroSeeder extends Seeder
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
            $this->command->info('Criando unidades de saúde para associar aos enfermeiros...');
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

        $usedCorens = [];

        $generateUniqueCoren = function () use ($faker, &$usedCorens) {
            do {
                $coren = $faker->numerify('######') . '-' . $faker->stateAbbr;
            } while (in_array($coren, $usedCorens));
            $usedCorens[] = $coren;
            return $coren;
        };

        // LOOP ALTERADO PARA 50 INSERTS
        for ($i = 0; $i < 120; $i++) {
            $nomeCompleto = $faker->name();
            $email = $faker->unique()->safeEmail();

            $usuario = Usuario::create([
                'nomeUsuario'       => $nomeCompleto,
                'emailUsuario'      => $email,
                'senhaUsuario'      => Hash::make('password'),
                'statusAtivoUsuario' => $faker->boolean(90),
            ]);

            $enfermeiro = Enfermeiro::create([
                'id_usuario'                => $usuario->idUsuarioPK,
                'nomeEnfermeiro'            => $nomeCompleto,
                'emailEnfermeiro'           => $email,
                'corenEnfermeiro'           => $generateUniqueCoren(),
                'especialidadeEnfermeiro'   => $faker->randomElement(['Enfermeiro Geral', 'Enfermeiro Pediátrico', 'Enfermeiro de Urgência']),
                'genero'                    => $faker->randomElement(['Masculino', 'Feminino']),
            ]);

            $unidadesAleatorias = $faker->randomElements($unidadeIds, $faker->numberBetween(1, 2));
            $enfermeiro->unidades()->sync($unidadesAleatorias);
        }
    }
}