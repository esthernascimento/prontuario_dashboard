<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Enfermeiro;
use App\Models\Paciente;
use App\Models\Prontuario; 
use App\Models\Consulta; // Importado para as métricas
use Carbon\Carbon; // Importado para lidar com datas

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Autenticação e Busca do Enfermeiro
        $usuario = Auth::guard('enfermeiro')->user();

        if (!$usuario) {
            return redirect()->route('enfermeiro.login')->with('error', 'Faça login para acessar o dashboard.');
        }

        // Assumindo que a coluna na tbEnfermeiro é 'id_usuario'
        $enfermeiro = Enfermeiro::where('id_usuario', $usuario->idUsuarioPK)->first();

        if (!$enfermeiro) {
            return redirect()->route('enfermeiro.login')->with('error', 'Não foi possível carregar os dados do enfermeiro.');
        }

        // 2. Busca da Unidade de Atuação (Filtro Chave)
        // Assumindo que o Model Enfermeiro tem a relação 'unidades()'
        $unidadeEnfermeiro = $enfermeiro->unidades()->first();
        $unidadeId = $unidadeEnfermeiro ? $unidadeEnfermeiro->idUnidadePK : null;
        $unidadeNome = $unidadeEnfermeiro->nomeUnidade ?? 'N/A';
        $hoje = Carbon::today();

        // 3. 📊 Lógica das Métricas (Filtradas por Unidade/Enfermeiro)
        
        // 3.1. Atendimentos no Dia (Triagens Concluídas hoje E na Unidade)
        $atendimentosDia = 0;
        if ($unidadeId) {
            $atendimentosDia = Consulta::where('idUnidadeFK', $unidadeId)
                ->whereDate('created_at', $hoje)
                ->whereNotNull('idEnfermeiroFK') // Triagem realizada
                ->where(function($query) {
                    $query->where('status_atendimento', 'AGUARDANDO_CONSULTA')
                          ->orWhere('status_atendimento', 'FINALIZADO');
                })
                ->count();
        }

        // 3.2. Pacientes Próprios (Pacientes que este enfermeiro já atendeu/triou em qualquer período)
        $pacientesProprios = Consulta::where('idEnfermeiroFK', $enfermeiro->idEnfermeiroPK)
            ->distinct('idPacienteFK') // Conta pacientes ÚNICOS
            ->count('idPacienteFK');

        // 3.3. Agendamentos Hoje (Pacientes aguardando triagem hoje E na Unidade)
        $agendamentosHoje = 0;
        if ($unidadeId) {
            $agendamentosHoje = Consulta::where('idUnidadeFK', $unidadeId)
                ->whereDate('created_at', $hoje)
                ->where('status_atendimento', 'AGUARDANDO_TRIAGEM')
                ->count();
        }
        
        // --- Outras Métricas (Gênero Global) ---
        $homens = Enfermeiro::where('genero', 'Masculino')->count();
        $mulheres = Enfermeiro::where('genero', 'Feminino')->count();

        $dadosGeneroEnfermeiro = [
            'Homens' => $homens,
            'Mulheres' => $mulheres,
        ];

        // 4. Retorna a view com as variáveis atualizadas
        return view('enfermeiro.dashboardEnfermeiro', [
            'enfermeiro' => $enfermeiro,
            'nomeEnfermeiro' => $enfermeiro->nomeEnfermeiro, // Nome do enfermeiro para o banner
            'unidadeAtuacao' => $unidadeNome, // Nome da Unidade para o card
            
            // Variáveis dos cards corrigidas
            'atendimentosDia' => $atendimentosDia,
            'pacientesProprios' => $pacientesProprios,
            'agendamentosHoje' => $agendamentosHoje,
            
            'dadosGeneroEnfermeiro' => $dadosGeneroEnfermeiro,
        ]);
    }
}