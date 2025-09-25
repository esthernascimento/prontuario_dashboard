<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class DashboardController extends Controller
{
    public function index()
    {
        $adminCount = Medico::count(); 

        $patientsCount = Paciente::count();

        $pendingExamsCount = 0;

        $medicosPorEspecialidade = DB::table('tbMedico')
            ->select('especialidadeMedico', DB::raw('count(*) as total'))
            ->whereNotNull('especialidadeMedico') 
            ->groupBy('especialidadeMedico')
            ->orderBy('total', 'desc') 
            ->get();


            return view('admin.dashboard', compact(
                'adminCount',
                'medicosPorEspecialidade' 
            ));


    }
}
