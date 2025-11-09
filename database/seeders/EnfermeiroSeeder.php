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
        
        // Pega as unidades que o UnidadeSeeder criou
        $unidades = Unidade::all();
        if ($unidades->isEmpty()) {
            $this->command->error('Nenhuma unidade encontrada. Rode o UnidadeSeeder primeiro.');
            return;
        }
        $unidadeIds = $unidades->pluck('idUnidadePK')->toArray();
        $primeiraUnidadeId = $unidades->first()->idUnidadePK;

        // ================================================================
        // --- REMOVIDO: Bloco que criava unidades duplicadas ---
        // ================================================================

        $usedCorens = [];
        $generateUniqueCoren = function () use ($faker, &$usedCorens) {
            do {
                $coren = $faker->numerify('######') . '-' . $faker->stateAbbr;
            } while (in_array($coren, $usedCorens));
            $usedCorens[] = $coren;
            return $coren;
        };

        // ================================================================
        // --- ADICIONADO: Enfermeiro Fixo (ID 1) ---
        // ================================================================
        $emailFixo = 'enfermeiro.teste@prontuario.com';
        $corenFixo = '123456-SP';
        $usedCorens[] = $corenFixo; // Garante que não será usado pelo faker

        // Cria o Usuário para o Enfermeiro
        $usuarioEnf = Usuario::firstOrCreate(
            ['emailUsuario' => $emailFixo],
            [
                'nomeUsuario' => 'Ana Costa (Enfermeira)', // Nome na tbUsuario
                'senhaUsuario' => Hash::make('senha123'),
                'statusAtivoUsuario' => true,
            ]
        );

        // Cria o Enfermeiro
        $enfFixo = Enfermeiro::create([
            'id_usuario' => $usuarioEnf->idUsuarioPK,
            'nomeEnfermeiro' => 'Ana Costa', // Nome na tbEnfermeiro
            'emailEnfermeiro' => $emailFixo,
            'corenEnfermeiro' => $corenFixo,
            'especialidadeEnfermeiro' => 'Enfermeiro Geral',
            'genero' => 'Feminino',
        ]);
        
        // Associa o enfermeiro fixo à primeira unidade
        $enfFixo->unidades()->sync([$primeiraUnidadeId]);

        // ================================================================
        // --- Loop para os 119 enfermeiros aleatórios restantes ---
        // ================================================================
        for ($i = 0; $i < 119; $i++) { // <-- CORRIGIDO: de 120 para 119
            $nomeCompleto = $faker->name();
            $email = $faker->unique()->safeEmail();

            $usuario = Usuario::create([
                'nomeUsuario' => $nomeCompleto,
                'emailUsuario' => $email,
                'senhaUsuario' => Hash::make('password'),
                'statusAtivoUsuario' => $faker->boolean(90),
            ]);

            $enfermeiro = Enfermeiro::create([
                'id_usuario' => $usuario->idUsuarioPK,
                'nomeEnfermeiro' => $nomeCompleto,
                'emailEnfermeiro' => $email,
                'corenEnfermeiro' => $generateUniqueCoren(),
                'especialidadeEnfermeiro' => $faker->randomElement(['Enfermeiro Geral', 'Enfermeiro Pediátrico', 'Enfermeiro de Urgência', 'Enfermeiro Obstétrico']),
                'genero' => $faker->randomElement(['Masculino', 'Feminino']),
            ]);

            $unidadesAleatorias = $faker->randomElements($unidadeIds, $faker->numberBetween(1, 2));
            $enfermeiro->unidades()->sync($unidadesAleatorias);
        }
        
        $this->command->info('120 enfermeiros (1 fixo + 119 aleatórios) criados com sucesso!');
    }
}