<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;
use App\Models\Recepcionista; 

class AcolhimentoController extends Controller
{
    
    public function create()
    {
        $recepcionista = Auth::guard('recepcionista')->user();
        
        if (!$recepcionista) {
            return redirect()->route('recepcionista.login');
        }

        return view('recepcionista.acolhimento.create', compact('recepcionista'));
    }

    /**
     * Salva o novo atendimento (acolhimento).
     */
    public function store(Request $request)
    {
        // Validação
        $dadosValidados = $request->validate([
            'paciente_id' => 'required|exists:tbPaciente,idPaciente',
            'queixa_principal' => 'required|string|min:10', 
            'classificacao_risco' => 'required|string',
        ]);

        $consulta = new Consulta(); 
        
        $consulta->idPacienteFK = $dadosValidados['paciente_id'];
        $consulta->queixa_principal = $dadosValidados['queixa_principal'];
        $consulta->classificacao_risco = $dadosValidados['classificacao_risco'];
        
        $consulta->idRecepcionistaFK = Auth::guard('recepcionista')->id(); 
        
        $consulta->dataConsulta = now();
        $consulta->status_atendimento = 'AGUARDANDO_TRIAGEM';
        
        $consulta->save();

        return redirect()->route('recepcionista.dashboard')
                         ->with('success', 'Paciente encaminhado para triagem!');
    }
}