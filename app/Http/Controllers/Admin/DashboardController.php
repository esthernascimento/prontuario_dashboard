<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;

class DashboardController extends Controller
{
    public function index()
    {
        $adminCount = Medico::count(); 

        $patientsCount = Paciente::count();

        $pendingExamsCount = 0;

        return view('admin.dashboard', compact(
            'adminCount', 
          
        ));
    }
}
