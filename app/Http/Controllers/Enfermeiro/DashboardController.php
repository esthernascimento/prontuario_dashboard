<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Enfermeiro;
use App\Models\Paciente; // Adicionado para contar pacientes
use App\Models\Prontuario; // Adicionado para contar prontuários

class DashboardController extends Controller
{
   public function index()
{
    // Pega o usuário autenticado
    $usuario = Auth::guard('enfermeiro')->user();

    // Se não estiver logado
    if (!$usuario) {
        return redirect()->route('enfermeiro.login')->with('error', 'Faça login para acessar o dashboard.');
    }

    // Busca o enfermeiro pelo ID do usuário
    $enfermeiro = Enfermeiro::where('id_usuario', $usuario->idUsuarioPK)->first();

    // Se não encontrar o enfermeiro
    if (!$enfermeiro) {
        return redirect()->route('enfermeiro.login')->with('error', 'Não foi possível carregar os dados do enfermeiro.');
    }

    // 📊 Lógica dos dados do dashboard
    $patientsCount = \App\Models\Paciente::count();
    $prontuariosCount = \App\Models\Prontuario::count();

    $homens = Enfermeiro::where('genero', 'Masculino')->count();
    $mulheres = Enfermeiro::where('genero', 'Feminino')->count();

    $dadosGeneroEnfermeiro = [
        'Homens' => $homens,
        'Mulheres' => $mulheres,
    ];

    // Retorna a view com tudo (incluindo $enfermeiro pro template usar)
    return view('enfermeiro.dashboardEnfermeiro', [
        'enfermeiro' => $enfermeiro,
        'patientsCount' => $patientsCount,
        'prontuariosCount' => $prontuariosCount,
        'dadosGeneroEnfermeiro' => $dadosGeneroEnfermeiro,
    ]);
}
}