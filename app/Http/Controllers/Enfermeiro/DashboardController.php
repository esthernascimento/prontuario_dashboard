<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Enfermeiro;

class DashboardController extends Controller
{
    public function index()
    {
        // Utiliza o guard para recuperar o usuário autenticado
        $usuario = Auth::guard('enfermeiro')->user();

        if (!$usuario) {
            return redirect()->route('enfermeiro.login')->with('error', 'Faça login para acessar o dashboard.');
        }

        // Busca o enfermeiro relacionado ao usuário
        $enfermeiro = Enfermeiro::where('id_usuario', $usuario->idUsuarioPK)->first();

        $nome = $enfermeiro->nomeEnfermeiro ?? $usuario->nomeUsuario;
        $coren = $enfermeiro->corenEnfermeiro ?? null;
        
        // 📊 Lógica para o gráfico de gênero dos enfermeiros
        $homens = Enfermeiro::where('genero', 'Masculino')->count();
        $mulheres = Enfermeiro::where('genero', 'Feminino')->count();

        $dadosGeneroEnfermeiro = [
            'Homens' => $homens,
            'Mulheres' => $mulheres,
        ];

        return view('enfermeiro.dashboardEnfermeiro', [
            'nome' => $nome,
            'coren' => $coren,
            'adminsCount' => 5,
            'patientsCount' => 20,
            'pendingExamsCount' => 3,
            'ubsCount' => 2,
            'dadosGeneroEnfermeiro' => $dadosGeneroEnfermeiro, // Passa os dados de gênero para a view
        ]);
    }
}