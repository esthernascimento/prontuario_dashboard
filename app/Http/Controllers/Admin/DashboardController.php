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
        // Contagens para os cards
        $adminCount = Medico::count();
        $patientsCount = Paciente::count();
        $pendingExamsCount = 0; // Placeholder

        // Médicos por especialidade
        $medicosPorEspecialidade = DB::table('tbMedico')
            ->select('especialidadeMedico', DB::raw('count(*) as total'))
            ->whereNotNull('especialidadeMedico')
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
            $dadosLinha['meses'][] = $mes->format('M Y'); // Ex: Jan 2024
            
            // Usando a coluna correta para a tabela de médicos
            $dadosLinha['admins'][] = Medico::whereYear('dataCadastroMedico', $mes->year)
                ->whereMonth('dataCadastroMedico', $mes->month)
                ->count();
            
            // Usando 'created_at' para pacientes
            $dadosLinha['pacientes'][] = Paciente::whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month)
                ->count();
        }

        // 📊 Distribuição de gênero (Homens, Mulheres, Idosos)
        $homens = Paciente::where('genero', 'Masculino')->count();
        $mulheres = Paciente::where('genero', 'Feminino')->count();
        
        // Usando a coluna 'data_nasc' para calcular a idade
        $idosos = Paciente::where('data_nasc', '<=', Carbon::now()->subYears(60)->toDateString())->count();

        $dadosGenero = [
            'Homens'   => $homens,
            'Mulheres' => $mulheres,
            'Idosos'   => $idosos,
        ];

        // CORREÇÃO FINAL AQUI: Apontando para a view correta 'admin.dashboard'
        return view('admin.dashboard', compact(
            'adminCount',
            'patientsCount',
            'pendingExamsCount',
            'medicosPorEspecialidade',
            'dadosLinha',
            'dadosGenero'
        ));
    }
<<<<<<< HEAD
}
=======
}

>>>>>>> 994bc1bc5c0130793e5eeb04f4803ac8586ca5ed
