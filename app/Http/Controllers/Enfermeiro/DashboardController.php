<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Importado para queries de agrupamento
use App\Models\Enfermeiro;
use App\Models\Paciente;
use App\Models\Prontuario; 
use App\Models\Consulta;
use App\Models\AnotacaoEnfermagem; // NOVO: Importado para as métricas e gráficos
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Autenticação e Busca do Enfermeiro
        $usuario = Auth::guard('enfermeiro')->user();

        if (!$usuario) {
            return redirect()->route('enfermeiro.login')->with('error', 'Faça login para acessar o dashboard.');
        }

        $enfermeiro = Enfermeiro::where('id_usuario', $usuario->idUsuarioPK)->first();

        if (!$enfermeiro) {
            return redirect()->route('enfermeiro.login')->with('error', 'Não foi possível carregar os dados do enfermeiro.');
        }

        // 2. Busca da Unidade de Atuação (Filtro Chave)
        $unidadeEnfermeiro = $enfermeiro->unidades()->first();
        $unidadeId = $unidadeEnfermeiro ? $unidadeEnfermeiro->idUnidadePK : null;
        $unidadeNome = $unidadeEnfermeiro->nomeUnidade ?? 'N/A';
        $hoje = Carbon::today();

        // 3. 📊 Lógica das Métricas (Filtradas por Enfermeiro/Unidade)
        
        // 3.1. Triagens Concluídas Hoje (RESOLVENDO INCONSISTÊNCIA: Conta Anotações de Enfermagem criadas HOJE)
        $atendimentosDia = 0;
        if ($enfermeiro->idEnfermeiroPK) {
            $atendimentosDia = AnotacaoEnfermagem::where('idEnfermeiroFK', $enfermeiro->idEnfermeiroPK)
                ->whereDate('data_hora', $hoje)
                ->count();
        }

        // 3.2. Pacientes Próprios (Total de pacientes ÚNICOS triados por este enfermeiro em qualquer período)
        $pacientesProprios = AnotacaoEnfermagem::where('idEnfermeiroFK', $enfermeiro->idEnfermeiroPK)
            ->distinct('idPacienteFK')
            ->count('idPacienteFK');

        // 3.3. Agendamentos Hoje (Pacientes aguardando triagem hoje E na Unidade)
        $agendamentosHoje = 0;
        if ($unidadeId) {
            $agendamentosHoje = Consulta::where('idUnidadeFK', $unidadeId)
                ->whereDate('created_at', $hoje)
                ->where('status_atendimento', 'AGUARDANDO_TRIAGEM')
                ->count();
        }
        
        // 4. 📈 Dados para os Gráficos
        
        // NOVO: Dados para o Gráfico de Linha de Evolução de Triagens
        $dadosTriagensMes = $this->getTriagensPorMes($enfermeiro->idEnfermeiroPK);

        // Outras Métricas (Gênero Global)
        $homens = Enfermeiro::where('genero', 'Masculino')->count();
        $mulheres = Enfermeiro::where('genero', 'Feminino')->count();

        $dadosGeneroEnfermeiro = [
            'Homens' => $homens,
            'Mulheres' => $mulheres,
        ];

        // 5. Retorna a view com as variáveis atualizadas
        return view('enfermeiro.dashboardEnfermeiro', [
            'enfermeiro' => $enfermeiro,
            'nomeEnfermeiro' => $enfermeiro->nomeEnfermeiro,
            'unidadeAtuacao' => $unidadeNome,
            
            // Variáveis dos cards corrigidas
            'atendimentosDia' => $atendimentosDia,
            'pacientesProprios' => $pacientesProprios,
            'agendamentosHoje' => $agendamentosHoje,
            
            // Variável do gráfico atualizada
            'dadosTriagensMes' => $dadosTriagensMes,
            
            'dadosGeneroEnfermeiro' => $dadosGeneroEnfermeiro,
        ]);
    }

    /**
     * Busca o número de triagens realizadas pelo enfermeiro logado nos últimos 6 meses.
     * @param int $enfermeiroId O PK do enfermeiro logado
     * @return array
     */
    private function getTriagensPorMes(int $enfermeiroId)
    {
        // 1. Prepara a lista de meses dos últimos 6 meses (para garantir 6 pontos no gráfico)
        $mesesParaRotulo = [];
        $meses = []; // Array para armazenar os dados do banco
        $hoje = Carbon::now();
        
        for ($i = 5; $i >= 0; $i--) {
            $data = $hoje->copy()->subMonths($i);
            // Formata o nome do mês em Português
            $mesNome = $data->locale('pt_BR')->isoFormat('MMM'); 
            // Usa YYYY-MM como chave para ordenação e mapeamento
            $mesesParaRotulo[$data->format('Y-m')] = ucfirst($mesNome); 
            $meses[$data->format('Y-m')] = 0;
        }

        // 2. Consulta ao banco: Agrupa triagens realizadas pelo enfermeiro nos últimos 6 meses
        $dataInicial = $hoje->copy()->subMonths(5)->startOfMonth();

        $resultados = AnotacaoEnfermagem::select(
               // AGORA
                DB::raw('COUNT(*) as total'),
                DB::raw('DATE_FORMAT(data_hora, "%Y-%m") as ano_mes')
            )
            ->where('idEnfermeiroFK', $enfermeiroId)
            ->where('data_hora', '>=', $dataInicial)
            ->groupBy('ano_mes')
            ->orderBy('ano_mes', 'asc')
            ->get();
        
        // 3. Mapeia os resultados para o array de meses, preenchendo os meses sem dados com 0
        foreach ($resultados as $resultado) {
            $meses[$resultado->ano_mes] = $resultado->total;
        }

        // 4. Retorna no formato [labels, data] para o Chart.js
        return [
            'labels' => array_values($mesesParaRotulo), // Rótulos dos meses formatados (pt-BR)
            'data' => array_values($meses) // Contagem de triagens
        ];
    }
}

