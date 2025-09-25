<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $adminCount = Medico::count();
        $patientsCount = Paciente::count();
        $pendingExamsCount = 0;

        // Médicos por especialidade
        $medicosPorEspecialidade = DB::table('tbMedico')
            ->select('especialidadeMedico', DB::raw('count(*) as total'))
            ->whereNotNull('especialidadeMedico')
            ->groupBy('especialidadeMedico')
            ->orderBy('total', 'desc')
            ->get();

        // 📊 Crescimento de Admins e Pacientes por mês (Últimos 6 meses)
        $meses = [];
        $dadosLinha = [
            'meses' => [],
            'admins' => [],
            'pacientes' => [],
        ];

        // Cria um array com os últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $dadosLinha['meses'][] = $mes->format('M Y'); // Ex: Jan 2024
            
            // Busca dados de admins (médicos) e pacientes para cada mês
            $dadosLinha['admins'][] = Medico::whereYear('dataCadastroMedico', $mes->year)
                ->whereMonth('dataCadastroMedico', $mes->month)
                ->count();
            
            $dadosLinha['pacientes'][] = Paciente::whereYear('dataCadastroPaciente', $mes->year)
                ->whereMonth('dataCadastroPaciente', $mes->month)
                ->count();
        }

        // 📊 Distribuição de gênero (Homens, Mulheres, Idosos)
        $homens = Paciente::where('genero', 'Masculino')->count();
        $mulheres = Paciente::where('genero', 'Feminino')->count();
        $idosos = Paciente::whereRaw('TIMESTAMPDIFF(YEAR, dataNascPaciente, CURDATE()) >= 60')->count();

        $dadosGenero = [
            'Homens'   => $homens,
            'Mulheres' => $mulheres,
            'Idosos'   => $idosos,
        ];

        return view('admin.dashboard', compact(
            'adminCount',
            'patientsCount',
            'pendingExamsCount',
            'medicosPorEspecialidade',
            'dadosLinha',
            'dadosGenero'
        ));
    }
}
