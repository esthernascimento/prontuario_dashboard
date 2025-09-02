<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Ubs;

class DashboardController extends Controller
{
    public function index()
    {
        // Contar médicos cadastrados
        $adminCount = Medico::count(); 

        // Contar pacientes cadastrados
        $pacienteCount = Paciente::count();

        //Quando tivermos registros de unidades reativamos a tag
        // Contar UBS cadastradas
        //$ubsCount = Ubs::count();

        // Passa os dados para a view
        return view('admin.dashboard', compact(
            'adminCount', 
            'pacienteCount', 
            //'ubsCount',
        ));
    }
}