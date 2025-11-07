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
use Carbon\Carbon;

class MedicoDashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('medico.login')->with('error', 'Faça login para acessar o dashboard.');
        }

        // 🔥 CORREÇÃO: Buscar o médico relacionado ao usuário logado
        $medico = Medico::where('id_usuarioFK', $usuario->idUsuarioPK)->first();

        if (!$medico) {
            return redirect()->route('medico.login')->with('error', 'Médico não encontrado.');
        }

        // --- CONSULTAS AO BANCO DE DADOS ---
        $patientsCount = Paciente::where('statusPaciente', 1)->count();
        $prontuariosCount = Prontuario::count();
        $totalExamsCount = Exame::count();

        // 4. Atendimentos por Mês
        $atendimentosPorMes = Consulta::select(
                DB::raw('MONTH(dataConsulta) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('dataConsulta', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->mapWithKeys(function ($item) {
                Carbon::setLocale('pt_BR');
                return [Carbon::create()->month($item->mes)->monthName => $item->total];
            });
        
        // 5. Evolução de Atendimentos
        $evolucaoAtendimentos = Consulta::select(
                DB::raw('YEAR(dataConsulta) as ano'),
                DB::raw('MONTH(dataConsulta) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('dataConsulta', '>=', Carbon::now()->subYear())
            ->groupBy('ano', 'mes')
            ->orderBy('ano')
            ->orderBy('mes')
            ->get()
            ->map(function ($item) {
                Carbon::setLocale('pt_BR');
                return [
                    'label' => Carbon::create($item->ano, $item->mes)->format('M/Y'),
                    'total' => $item->total,
                ];
            });

        return view('medico.dashboardMedico', [
            'nome' => $medico->nomeMedico, // 🔥 Agora usando o nome do médico
            'crm' => $medico->crmMedico,
            'patientsCount' => $patientsCount,
            'prontuariosCount' => $prontuariosCount,
            'totalExamsCount' => $totalExamsCount,
            'atendimentosPorMes' => $atendimentosPorMes,
            'evolucaoAtendimentos' => $evolucaoAtendimentos,
        ]);
    }
}