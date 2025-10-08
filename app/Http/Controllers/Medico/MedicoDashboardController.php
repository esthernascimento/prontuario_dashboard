<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Prontuario;
use App\Models\Consulta;
use App\Models\Exame;
use Illuminate\Support\Facades\DB; 

class MedicoDashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('medico.login')->with('error', 'Faça login para acessar o dashboard.');
        }

        $medico = Medico::where('id_usuarioFK', $usuario->idUsuarioPK)->first();

        if (!$medico) {
            return redirect()->route('medico.login')->with('error', 'Médico não encontrado.');
        }

        // --- CONSULTAS AO BANCO DE DADOS ---

        // 1. Contagem de Pacientes Ativos
        $patientsCount = Paciente::where('statusPaciente', 1)->count();
        
        // 2. Contagem de Prontuários Registrados
        $prontuariosCount = Prontuario::count();

        // 3. Contagem de Exames Totais (ajustado porque não há coluna de status)
        $totalExamsCount = Exame::withTrashed()->count();

        // 4. Atendimentos por Mês (para o gráfico de barras)
        $atendimentosPorMes = Consulta::select(
                DB::raw('MONTH(dataConsulta) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        
        // 5. Evolução de Atendimentos (para o gráfico de linha)
        $evolucaoAtendimentos = Consulta::select(
                DB::raw('YEAR(dataConsulta) as ano'),
                DB::raw('MONTH(dataConsulta) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('ano', 'mes')
            ->orderBy('ano')
            ->orderBy('mes')
            ->limit(12)
            ->get();

        // --- ENVIANDO OS DADOS PARA A VIEW ---

        return view('medico.dashboardMedico', [
            'nome' => $medico->nomeMedico,
            'crm' => $medico->crmMedico,
            'patientsCount' => $patientsCount,
            'prontuariosCount' => $prontuariosCount,
            'totalExamsCount' => $totalExamsCount,
            'atendimentosPorMes' => $atendimentosPorMes,
            'evolucaoAtendimentos' => $evolucaoAtendimentos,
        ]);
    }
}