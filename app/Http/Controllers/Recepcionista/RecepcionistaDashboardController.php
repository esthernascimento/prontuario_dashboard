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
        // 1. ALTERAÇÃO: Removida a busca por unidades, pois não são mais usadas na view
        // $unidades = Unidade::where('statusAtivoUnidade', true)
        //                     ->orderBy('nomeUnidade')
        //                     ->get();
                            
        // 2. ALTERAÇÃO: Não passamos mais a variável 'unidades' para a view
        return view('recepcionista.dashboardRecepcionista');
    }

    public function store(Request $request)
    {
        // 3. ALTERAÇÃO: Removida a validação 'unidade_id' => 'required|exists:tbUnidade,idUnidadePK'
        $dadosValidados = $request->validate([
            'paciente_id' => 'required|exists:tbPaciente,idPaciente',
            'queixa_principal' => 'required|string|min:10',
        ]);

        $consulta = new Consulta(); 
        
        $consulta->idPacienteFK = $dadosValidados['paciente_id'];
        $consulta->queixa_principal = $dadosValidados['queixa_principal'];
        
        // --- 4. ALTERAÇÃO: Definição de Valor Padrão para Unidade ---
        
        // EXIGE QUE VOCÊ DEFINA UMA UNIDADE PADRÃO OU BASEADA NO RECEPCIONISTA LOGADO.
        // Se a unidade for obrigatória no seu banco de dados, você precisa preenchê-la.
        
        // 1. Tenta pegar a Unidade do usuário logado (assumindo que existe um idUnidadeFK)
        // Se não existir, usa 1 como ID de fallback (ajuste este ID conforme necessário!)
        $unidadePadraoId = Auth::user()->idUnidadeFK ?? 1; 

        $consulta->idUnidadeFK = $unidadePadraoId;
        
        // 2. Busca o nome da Unidade para preencher o campo 'unidade' (se for necessário)
        $unidade = Unidade::find($unidadePadraoId);
        $consulta->unidade = $unidade?->nomeUnidade ?? 'Unidade Padrão Fixa';
        
        // --- Fim da Alteração de Unidade ---
        
        $consulta->idRecepcionistaFK = Auth::guard('recepcionista')->id(); 
        $consulta->dataConsulta = now();
        $consulta->status_atendimento = 'AGUARDANDO_TRIAGEM';
        
        $consulta->save();

        return redirect()->route('recepcionista.dashboard')
                             ->with('success', 'Paciente encaminhado para triagem!');
    }
}