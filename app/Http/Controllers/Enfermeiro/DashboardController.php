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
        // Pega o usuário autenticado, usando o "guard" de enfermeiro
        $usuario = Auth::guard('enfermeiro')->user();

        // Se não houver usuário logado, redireciona para a página de login
        if (!$usuario) {
            return redirect()->route('enfermeiro.login')->with('error', 'Faça login para acessar o dashboard.');
        }

        // Busca as informações completas do enfermeiro
        $enfermeiro = Enfermeiro::where('id_usuario', $usuario->idUsuarioPK)->first();

        // Obtém o nome do enfermeiro logado, usando o do banco ou um padrão
        $nome = $enfermeiro->nomeEnfermeiro ?? 'Enfermeiro';

        // 📊 Lógica de Negócio - Busca de dados 📊
        // Conta o total de pacientes na tabela 'pacientes'
        $patientsCount = Paciente::count();

        // Conta o total de prontuários na tabela 'prontuarios'
        $prontuariosCount = Prontuario::count();

        // Conta o número de enfermeiros por gênero para o gráfico de donut
        $homens = Enfermeiro::where('genero', 'Masculino')->count();
        $mulheres = Enfermeiro::where('genero', 'Feminino')->count();

        $dadosGeneroEnfermeiro = [
            'Homens' => $homens,
            'Mulheres' => $mulheres,
        ];
        
        // Passa todos os dados coletados para a view
        return view('enfermeiro.dashboardEnfermeiro', [
            'nome' => $nome,
            'patientsCount' => $patientsCount,
            'prontuariosCount' => $prontuariosCount,
            'dadosGeneroEnfermeiro' => $dadosGeneroEnfermeiro,
        ]);
    }
}