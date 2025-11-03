<?php

namespace App\Http\Controllers\unidade;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Enfermeiro;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        $nomeAdmin = $admin->nomeAdmin ?? 'Administrador';
        
        $medicosCount = Medico::count();
        $patientsCount = Paciente::count();
        $nursesCount = Enfermeiro::count();
        $unidadesCount = Unidade::count();

        // 📊 Gráfico de Profissionais por Área
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        $medicosPorEspecialidade = DB::table('tbMedico')
            ->select('especialidadeMedico', DB::raw('count(*) as total'))
            ->whereNotNull('especialidadeMedico')
            ->where(DB::raw("TRIM(especialidadeMedico)"), '!=', '')
            ->groupBy('especialidadeMedico')
            ->orderBy('total', 'desc')
            ->get();
        
        // 📊 Crescimento de Pacientes por mês (Últimos 6 meses)
        $dadosLinha = [
            'meses' => [],
            'pacientes' => [],
        ];
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $dadosLinha['meses'][] = $mes->format('M Y');
            $dadosLinha['pacientes'][] = Paciente::whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month)
                ->count();
        }

        // 📊 Distribuição de gênero e idosos
        $totalPacientes = Paciente::count();
        $homens = Paciente::where('generoPaciente', 'Masculino')->count();
        $mulheres = Paciente::where('generoPaciente', 'Feminino')->count();
        $idososCount = Paciente::where('dataNascPaciente', '<=', Carbon::now()->subYears(60)->toDateString())->count();

        $percentualIdosos = $totalPacientes > 0 ? round(($idososCount / $totalPacientes) * 100) : 0;
        
        $dadosGenero = [
            'Homens' => $homens,
            'Mulheres' => $mulheres,
        ];
        
        // 🗺️ Dados para o novo gráfico de Unidades por Região
        $regioesBrasil = [
            'Norte' => ['AC', 'AP', 'AM', 'PA', 'RO', 'RR', 'TO'],
            'Nordeste' => ['AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE'],
            'Centro-Oeste' => ['DF', 'GO', 'MT', 'MS'],
            'Sudeste' => ['ES', 'MG', 'RJ', 'SP'],
            'Sul' => ['PR', 'RS', 'SC']
        ];
        $unidadesPorRegiao = [];
        foreach ($regioesBrasil as $regiao => $ufs) {
            $unidadesPorRegiao[$regiao] = Unidade::whereIn('ufUnidade', $ufs)->count();
        }

        return view('admin.dashboard', compact(
            'nomeAdmin',
            'medicosCount',
            'patientsCount',
            'nursesCount',
            'unidadesCount',
            'medicosPorEspecialidade',
            'dadosLinha',
            'dadosGenero',
            'percentualIdosos', // Nova variável para o card de idosos
            'unidadesPorRegiao'
        ));
    }
}