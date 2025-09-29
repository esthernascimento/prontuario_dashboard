<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Medico;

class MedicoDashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('medico.login')->with('error', 'Faça login para acessar o dashboard.');
        }

        $medico = Medico::where('id_usuarioFK', $usuario->idUsuarioPK)->first();

        if (!$medico) {
            return redirect()->route('medico.login')->with('error', 'Médico não encontrado.');
        }

        $nome = $medico->nomeMedico ?? $usuario->nomeUsuario;
        $crm = $medico->crmMedico ?? null;

        $dadosGeneroMedico = [
            'Homens' => Medico::where('genero', 'Masculino')->count(),
            'Mulheres' => Medico::where('genero', 'Feminino')->count(),
        ];

        return view('medico.dashboardMedico', [
            'nome' => $nome,
            'crm' => $crm,
            'adminsCount' => 3,
            'patientsCount' => 15,
            'pendingExamsCount' => 4,
            'ubsCount' => 1,
            'dadosGeneroMedico' => $dadosGeneroMedico,
        ]);
    }
}
