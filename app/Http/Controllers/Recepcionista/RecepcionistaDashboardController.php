<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;
use App\Models\Unidade;

class RecepcionistaDashboardController extends Controller
{

    public function index()
    {

        
        $unidades = Unidade::where('statusAtivoUnidade', true)
                            ->orderBy('nomeUnidade')
                            ->get();
                            
        return view('recepcionista.dashboardRecepcionista', compact('unidades'));
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'paciente_id' => 'required|exists:tbPaciente,idPaciente',
            'unidade_id' => 'required|exists:tbUnidade,idUnidadePK',
            'queixa_principal' => 'required|string|min:10',
        ]);

        $consulta = new Consulta(); 
        
        $consulta->idPacienteFK = $dadosValidados['paciente_id'];
        $consulta->queixa_principal = $dadosValidados['queixa_principal'];
        $consulta->idUnidadeFK = $dadosValidados['unidade_id'];
        
        $unidade = Unidade::find($dadosValidados['unidade_id']);
        $consulta->unidade = $unidade?->nomeUnidade;
   
        $consulta->idRecepcionistaFK = Auth::guard('recepcionista')->id(); 
        $consulta->dataConsulta = now();
        $consulta->status_atendimento = 'AGUARDANDO_TRIAGEM';
        
        $consulta->save();

        return redirect()->route('recepcionista.dashboard')
                         ->with('success', 'Paciente encaminhado para triagem!');
    }
}