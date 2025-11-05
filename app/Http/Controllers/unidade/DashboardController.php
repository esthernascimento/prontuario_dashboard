<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// --- Models, DB e Carbon removidos, pois não são mais usados ---
// use App\Models\Medico;
// use App\Models\Paciente;
// use App\Models\Enfermeiro;
// use App\Models\Unidade;
// use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. OBTÉM A UNIDADE LOGADA (Isto é o que "deixa apenas o login")
        $unidade = Auth::guard('unidade')->user();

        if (!$unidade) {
             return redirect()->route('unidade.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        $nomeUnidade = $unidade->nomeUnidade ?? 'Unidade';

        // 2. Definimos valores padrão (vazios) que a sua view espera
        $medicosCount = 0;
        $patientsCount = 0;
        $nursesCount = 0;
        
        // Gráficos (vazios)
        $medicosPorEspecialidade = collect(); // Um array/coleção vazia
        $dadosLinha = [
            'meses' => [],
            'pacientes' => [],
        ];
        $dadosGenero = [
            'Homens' => 0,
            'Mulheres' => 0,
        ];
        $percentualIdosos = 0;

        // 3. Retorna a View correta da Unidade com os dados vazios
        return view('unidade.dashboardUnidade', compact(
            'nomeUnidade',
            'medicosCount',
            'patientsCount',
            'nursesCount',
            'medicosPorEspecialidade',
            'dadosLinha',
            'dadosGenero',
            'percentualIdosos'
        ));
    }
}