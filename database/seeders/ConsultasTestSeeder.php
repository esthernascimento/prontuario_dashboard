<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Prontuario;
use App\Models\Alergia;      // ADICIONADO
use App\Models\Medicamento;  // ADICIONADO
use App\Models\Exame;        // ADICIONADO
use Carbon\Carbon;

class ConsultasTestSeeder extends Seeder
{
    public function run(): void
    {
        $paciente = Paciente::find(1);
        $medico = Medico::first();
        $prontuario = Prontuario::firstOrCreate(
            ['idPacienteFK' => $paciente->idPaciente],
            ['dataAbertura' => now()->toDateString()]
        );
        $unidade = \App\Models\Unidade::first();
        $enfermeiro = \App\Models\Enfermeiro::first();
        $recepcionista = \App\Models\Recepcionista::first();

        if (!$paciente || !$medico || !$unidade || !$enfermeiro || !$recepcionista) {
            $this->command->error('Seeders de Paciente, Medico, Unidade, Enfermeiro ou Recepcionista não rodaram. Abortando ConsultasTestSeeder.');
            return;
        }
        
        // --- DADOS ADICIONAIS ---
        
        // 1. Criar Alergias para o Paciente 1
        Alergia::firstOrCreate(['idPacienteFK' => $paciente->idPaciente, 'descAlergia' => 'Dipirona']);
        Alergia::firstOrCreate(['idPacienteFK' => $paciente->idPaciente, 'descAlergia' => 'Amendoim']);
        
        // --- Criação das Consultas ---

        $consulta1 = Consulta::create([
            'idPacienteFK' => $paciente->idPaciente,
            'idProntuarioFK' => $prontuario->idProntuarioPK,
            'idMedicoFK' => $medico->idMedicoPK,
            'idEnfermeiroFK' => $enfermeiro->idEnfermeiroPK,
            'idRecepcionistaFK' => $recepcionista->idRecepcionistaPK,
            'idUnidadeFK' => $unidade->idUnidadePK,
            'unidade' => $unidade->nomeUnidade,
            'nomeMedico' => $medico->nomeMedico,
            'crmMedico' => $medico->crmMedico,
            'queixa_principal' => 'Febre alta e tosse seca há 2 dias.',
            'classificacao_risco' => 'amarelo',
            'status_atendimento' => 'FINALIZADO',
            'dataConsulta' => Carbon::now()->subDays(3),
            'observacoes' => 'Paciente diagnosticado com virose. Prescrito repouso e hidratação.',
            'examesSolicitados' => "Nenhum",
            'medicamentosPrescritos' => "Dipirona 500mg (se febre)\nRepouso",
            'created_at' => Carbon::now()->subDays(3),
            'updated_at' => Carbon::now()->subDays(3),
        ]);

        $consulta2 = Consulta::create([
            'idPacienteFK' => $paciente->idPaciente,
            'idProntuarioFK' => $prontuario->idProntuarioPK,
            'idMedicoFK' => $medico->idMedicoPK,
            'idEnfermeiroFK' => $enfermeiro->idEnfermeiroPK,
            'idRecepcionistaFK' => $recepcionista->idRecepcionistaPK,
            'idUnidadeFK' => $unidade->idUnidadePK,
            'unidade' => $unidade->nomeUnidade,
            'nomeMedico' => $medico->nomeMedico,
            'crmMedico' => $medico->crmMedico,
            'queixa_principal' => 'Dor no joelho direito após jogar futebol.',
            'classificacao_risco' => 'verde',
            'status_atendimento' => 'FINALIZADO',
            'dataConsulta' => Carbon::now()->subMonth(1),
            'observacoes' => 'Entorse leve. Recomendado gelo e anti-inflamatório.',
            'examesSolicitados' => "Raio-X do Joelho Direito",
            'medicamentosPrescritos' => "Ibuprofeno 600mg (3x/dia por 5 dias)\nParacetamol 750mg (se dor)",
            'created_at' => Carbon::now()->subMonth(1),
            'updated_at' => Carbon::now()->subMonth(1),
        ]);

        // 2. Criar Medicamentos para a Consulta 2
        Medicamento::create([
            'idConsultaFK' => $consulta2->idConsultaPK,
            'idProntuarioFK' => $prontuario->idProntuarioPK,
            'idPacienteFK' => $paciente->idPaciente,
            'nomeMedicamento' => 'Ibuprofeno 600mg',
            'descMedicamento' => 'Ibuprofeno 600mg (3x/dia por 5 dias)',
        ]);
         Medicamento::create([
            'idConsultaFK' => $consulta2->idConsultaPK,
            'idProntuarioFK' => $prontuario->idProntuarioPK,
            'idPacienteFK' => $paciente->idPaciente,
            'nomeMedicamento' => 'Paracetamol 750mg',
            'descMedicamento' => 'Paracetamol 750mg (se dor)',
        ]);

        $consulta3 = Consulta::create([
            'idPacienteFK' => $paciente->idPaciente,
            'idProntuarioFK' => $prontuario->idProntuarioPK,
            'idMedicoFK' => $medico->idMedicoPK,
            'idEnfermeiroFK' => $enfermeiro->idEnfermeiroPK,
            'idRecepcionistaFK' => $recepcionista->idRecepcionistaPK,
            'idUnidadeFK' => $unidade->idUnidadePK,
            'unidade' => $unidade->nomeUnidade,
            'nomeMedico' => $medico->nomeMedico,
            'crmMedico' => $medico->crmMedico,
            'queixa_principal' => 'Check-up de rotina.',
            'classificacao_risco' => 'azul',
            'status_atendimento' => 'FINALIZADO',
            'dataConsulta' => Carbon::now()->subMonths(3),
            'observacoes' => 'Paciente saudável. Exames de sangue normais.',
            'examesSolicitados' => "Hemograma Completo\nGlicemia de Jejum",
            'medicamentosPrescritos' => "Nenhum",
            'created_at' => Carbon::now()->subMonths(3),
            'updated_at' => Carbon::now()->subMonths(3),
        ]);

        // 3. Criar Exames para a Consulta 3
        Exame::create([
            'idConsultaFK' => $consulta3->idConsultaPK,
            'idProntuarioFK' => $prontuario->idProntuarioPK,
            'idPacienteFK' => $paciente->idPaciente,
            'nomeExame' => 'Hemograma Completo',
            'descExame' => 'Hemograma Completo',
            'dataExame' => $consulta3->dataConsulta,
            'statusExame' => 'SOLICITADO'
        ]);
        Exame::create([
            'idConsultaFK' => $consulta3->idConsultaPK,
            'idProntuarioFK' => $prontuario->idProntuarioPK,
            'idPacienteFK' => $paciente->idPaciente,
            'nomeExame' => 'Glicemia de Jejum',
            'descExame' => 'Glicemia de Jejum',
            'dataExame' => $consulta3->dataConsulta,
            'statusExame' => 'SOLICITADO'
        ]);
        
        $this->command->info('Consultas de teste, alergias, exames e medicamentos criados para o Paciente 1!');
    }
}