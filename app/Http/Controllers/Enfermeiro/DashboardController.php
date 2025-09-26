<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Session::has('enfermeiro_id')) {
            return redirect()->route('enfermeiro.login');
        }

        return view('enfermeiro.dashboardEnfermeiro', [
            'nome' => Session::get('enfermeiro_nome'),
            'adminsCount' => 5,   // exemplo
            'patientsCount' => 20,
            'pendingExamsCount' => 3,
            'ubsCount' => 2
        ]);
    }
}
