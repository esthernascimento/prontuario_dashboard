<?php

namespace Database\Seeders;

use App\Models\Medico;
use App\Models\Usuario;
use App\Models\Unidade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        
        // Pega as unidades que o UnidadeSeeder criou
        $unidades = Unidade::all();
        if ($unidades->isEmpty()) {
            $this->command->error('Nenhuma unidade encontrada. Rode o UnidadeSeeder primeiro.');
            return;
        }
        $unidadeIds = $unidades->pluck('idUnidadePK')->toArray();
        $primeiraUnidadeId = $unidades->first()->idUnidadePK;

        $usedCrms = [];
        $generateUniqueCrm = function () use ($faker, &$usedCrms) {
            do {
                $crm = $faker->numerify('#####') . '/' . $faker->stateAbbr;
            } while (in_array($crm, $usedCrms));
            $usedCrms[] = $crm;
            return $crm;
        };

        // ================================================================
        // --- ADICIONADO: Médico Fixo (ID 1) ---
        // ================================================================
        $emailFixo = 'medico.teste@prontuario.com';
        $crmFixo = '12345/SP';
        $usedCrms[] = $crmFixo; // Garante que não será usado pelo faker

        // Cria o Usuário para o Médico
        $usuarioMedico = Usuario::firstOrCreate(
            ['emailUsuario' => $emailFixo],
            [
                'nomeUsuario' => 'Dr. House (Médico)', // Nome na tbUsuario
                'senhaUsuario' => Hash::make('senha123'),
                'statusAtivoUsuario' => true,
                'statusSenhaUsuario' => true,
            ]
        );

        // Cria o Médico
        $medicoFixo = Medico::create([
            'id_usuarioFK' => $usuarioMedico->idUsuarioPK,
            'nomeMedico' => 'Dr. Gregory House', // Nome na tbMedico
            'crmMedico' => $crmFixo,
            'especialidadeMedico' => 'Clínico Geral',
        ]);
        
        // Associa o médico fixo à primeira unidade
        $medicoFixo->unidades()->sync([$primeiraUnidadeId]);

   
        for ($i = 0; $i < 119; $i++) { // <-- CORRIGIDO: de 120 para 119
            $nomeCompleto = $faker->name();
            $email = $faker->unique()->safeEmail();

            $usuario = Usuario::create([
                'nomeUsuario' => $nomeCompleto,
                'emailUsuario' => $email,
                'senhaUsuario' => Hash::make('password'),
                'statusAtivoUsuario' => $faker->boolean(90),
                'statusSenhaUsuario' => true,
            ]);

            $medico = Medico::create([
                'id_usuarioFK' => $usuario->idUsuarioPK,
                'nomeMedico' => $nomeCompleto,
                'crmMedico' => $generateUniqueCrm(),
                'especialidadeMedico' => $faker->randomElement([
                    'Clínico Geral', 'Pediatra', 'Cardiologista', 'Dermatologista', 
                    'Ortopedista', 'Ginecologista', 'Neurologista', 'Psiquiatra'
                ]),
            ]);

            $unidadesAleatorias = $faker->randomElements($unidadeIds, $faker->numberBetween(1, 2));
            $medico->unidades()->sync($unidadesAleatorias);
        }
        
        $this->command->info('120 médicos (1 fixo + 119 aleatórios) criados com sucesso!');
    }
}