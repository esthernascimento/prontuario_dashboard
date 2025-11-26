<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Medico;
use App\Models\Enfermeiro;
use App\Models\Recepcionista;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Pega a unidade logada
        $unidade = Auth::guard('unidade')->user();

        if (!$unidade) {
            return redirect()->route('unidade.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        $nomeUnidade = $unidade->nomeUnidade ?? 'Unidade';

        // --- CONTAGEM DE PROFISSIONAIS ---
        $medicosCount = $unidade->medicos()->count();
        $nursesCount = $unidade->enfermeiros()->count();
        $recepcionistasCount = $unidade->recepcionistas()->count(); // Adicionado

        // --- MÉDICOS POR ESPECIALIDADE ---
        $medicosPorEspecialidade = DB::table('tbMedico')
            ->join('tbMedicoUnidade', 'tbMedico.idMedicoPK', '=', 'tbMedicoUnidade.idMedicoFK')
            ->where('tbMedicoUnidade.idUnidadeFK', $unidade->idUnidadePK)
            ->select('tbMedico.especialidadeMedico', DB::raw('COUNT(*) as total'))
            ->groupBy('tbMedico.especialidadeMedico')
            ->orderBy('total', 'desc')
            ->get();

        // --- DADOS PARA OS GRÁFICOS ---
        $dadosLinha = [
            'meses' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            'pacientes' => [45, 52, 61, 58, 70, 78],
        ];

        $dadosGenero = [
            'Homens' => 45,
            'Mulheres' => 55,
        ];

        return view('unidade.dashboardUnidade', compact(
            'nomeUnidade',
            'medicosCount',
            'nursesCount',
            'recepcionistasCount', 
            'medicosPorEspecialidade',
            'dadosLinha',
            'dadosGenero'
        ));
    }
}