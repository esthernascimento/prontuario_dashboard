<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta; // Seu model tbConsulta
use App\Models\Unidade;

class AcolhimentoController extends Controller
{
    /**
     * Mostra o formulário de acolhimento (a tela principal).
     */
    public function create()
    {
        $unidades = Unidade::orderBy('nomeUnidade')->get();
        return view('recepcionista.acolhimento.create', compact('unidades'));
    }

    /**
     * Salva o novo atendimento (acolhimento).
     */
    public function store(Request $request)
    {
        // Validação
        $dadosValidados = $request->validate([
            'paciente_id' => 'required|exists:tbPaciente,idPaciente',
            'unidade_id' => 'required|exists:tbUnidade,idUnidadePK',
            // REMOVIDO: 'classificacao_risco'
            'queixa_principal' => 'required|string|min:10', 
        ]);

        // Cria a consulta
        $consulta = new Consulta(); 
        
        $consulta->idPacienteFK = $dadosValidados['paciente_id'];
        $consulta->queixa_principal = $dadosValidados['queixa_principal'];
        $consulta->idUnidadeFK = $dadosValidados['unidade_id'];
        // Armazena também o nome para compatibilidade com telas existentes (se usado)
        $unidade = Unidade::find($dadosValidados['unidade_id']);
        $consulta->unidade = $unidade?->nomeUnidade;
        
        // REMOVIDO: $consulta->classificacao_risco
        
        $consulta->idRecepcionistaFK = Auth::id(); 
        $consulta->dataConsulta = now();
        $consulta->status_atendimento = 'AGUARDANDO_TRIAGEM';
        
        $consulta->save();

        return redirect()->route('recepcionista.dashboard')
                         ->with('success', 'Paciente encaminhado para triagem!');
    }
}

