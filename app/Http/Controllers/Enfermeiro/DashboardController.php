<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        Log::info('Tentativa de acesso ao dashboard', [
            'session_data' => Session::all(),
            'enfermeiro_id' => Session::get('enfermeiro_id')
        ]);
        
        if (!Session::has('enfermeiro_id')) {
            Log::warning('Acesso negado - sem sessão de enfermeiro');
            return redirect()->route('enfermeiro.login')
                ->with('error', 'Você precisa fazer login para acessar o dashboard.');
        }

        $enfermeiroId = Session::get('enfermeiro_id');
        $enfermeiroNome = Session::get('enfermeiro_nome');
        
        if (!$enfermeiroId || !$enfermeiroNome) {
            Log::warning('Dados de sessão inválidos', [
                'enfermeiro_id' => $enfermeiroId,
                'enfermeiro_nome' => $enfermeiroNome
            ]);
            
            Session::flush();
            return redirect()->route('enfermeiro.login')
                ->with('error', 'Sessão inválida. Faça login novamente.');
        }

        Log::info('Acesso ao dashboard autorizado', ['enfermeiro_id' => $enfermeiroId]);

        return view('enfermeiro.dashboardEnfermeiro', [
            'nome' => $enfermeiroNome,
            'coren' => Session::get('enfermeiro_coren'),
            'adminsCount' => 5,   // exemplo
            'patientsCount' => 20,
            'pendingExamsCount' => 3,
            'ubsCount' => 2
        ]);
    }
}
