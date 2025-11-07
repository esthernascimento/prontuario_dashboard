<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;
use App\Models\Unidade; // Certifique-se que Unidade está importada

class AcolhimentoController extends Controller
{
    /**
     * Mostra o formulário de acolhimento (a tela principal).
     */
    public function create()
    {
        // Busca todas as unidades para o <select>
        $unidades = Unidade::where('statusAtivoUnidade', true) // Apenas unidades ativas
                            ->orderBy('nomeUnidade')
                            ->get();
                            
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
            'unidade_id' => 'required|exists:tbUnidade,idUnidadePK', // Espera um 'unidade_id' do form
            'queixa_principal' => 'required|string|min:10',
        ]);

        // Cria a consulta
        $consulta = new Consulta(); 
        
        $consulta->idPacienteFK = $dadosValidados['paciente_id'];
        $consulta->queixa_principal = $dadosValidados['queixa_principal'];
        $consulta->idUnidadeFK = $dadosValidados['unidade_id'];
        
        // Busca o nome da unidade para salvar (boa prática)
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