<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Enfermeiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Contagens para os cards
        $adminCount = Medico::count();
        $patientsCount = Paciente::count();
        $pendingExamsCount = 0; 
        $nursesCount = Enfermeiro::count(); 

        
        // 📊 Gráfico de Profissionais por Área
        
        // 1. Desativa temporariamente o modo estrito do MySQL para evitar erro 1055
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        // 2. CORREÇÃO: Remove a lógica de 'Não Especificada' e filtra
        // agora apenas especialidades preenchidas (não NULL e não string vazia)
        $medicosPorEspecialidade = DB::table('tbMedico')
            ->select(
                'especialidadeMedico',
                DB::raw('count(*) as total')
            )
            // Filtra registros que tenham a especialidade definida (não nula)
            ->whereNotNull('especialidadeMedico')
            // E também filtra registros onde a especialidade não é uma string vazia após remover espaços
            ->where(DB::raw("TRIM(especialidadeMedico)"), '!=', '')
            ->groupBy('especialidadeMedico') 
            ->orderBy('total', 'desc')
            ->get();
        
        // 📊 Crescimento de Admins e Pacientes por mês (Últimos 6 meses)
        $dadosLinha = [
            'meses' => [],
            'admins' => [],
            'pacientes' => [],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $dadosLinha['meses'][] = $mes->format('M Y'); 
            
            $dadosLinha['admins'][] = Medico::whereYear('dataCadastroMedico', $mes->year)
                ->whereMonth('dataCadastroMedico', $mes->month)
                ->count();
            
            $dadosLinha['pacientes'][] = Paciente::whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month)
                ->count();
        }

        // 📊 Distribuição de gênero (Homens, Mulheres, Idosos)
        $homens = Paciente::where('generoPaciente', 'Masculino')->count();
        $mulheres = Paciente::where('generoPaciente', 'Feminino')->count();
        $idosos = Paciente::where('dataNascPaciente', '<=', Carbon::now()->subYears(60)->toDateString())->count();

        $dadosGenero = [
            'Homens'  => $homens,
            'Mulheres' => $mulheres,
            'Idosos'  => $idosos,
        ];

        // Variável 'medicosPorEspecialidade' já está formatada corretamente
        return view('admin.dashboard', compact(
            'adminCount',
            'patientsCount',
            'pendingExamsCount',
            'nursesCount',
            'medicosPorEspecialidade', // Variável crucial para o gráfico de barras
            'dadosLinha',
            'dadosGenero'
        ));
    }

}
