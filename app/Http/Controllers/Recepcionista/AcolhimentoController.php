<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta; // Seu model tbConsulta

class AcolhimentoController extends Controller
{
    /**
     * Mostra o formulário de acolhimento (a tela principal).
     */
    public function create()
    {
        // Esta é a view que estilizamos
        return view('recepcionista.acolhimento.create');
    }

    /**
     * Salva o novo atendimento (acolhimento).
     */
    public function store(Request $request)
    {
        // Validação
        $dadosValidados = $request->validate([
            
            // =================================================================
            // --- AQUI ESTÁ A CORREÇÃO ---
            // Trocamos 'idPacientePK' (que não existe) por 'idPaciente'
            // (que é a chave primária da tabela tbPaciente)
            'paciente_id' => 'required|exists:tbPaciente,idPaciente',
            // =================================================================
            
            'queixa_principal' => 'required|string|min:10', 
            'classificacao_risco' => 'required|string',
        ]);

        // Cria a consulta
        $consulta = new Consulta(); // Seu model tbConsulta
        
        // Esta linha (da correção anterior) está CORRETA.
        // Ela salva o ID do paciente na chave estrangeira da tbConsulta.
        $consulta->idPacienteFK = $dadosValidados['paciente_id'];
        
        $consulta->queixa_principal = $dadosValidados['queixa_principal'];
        $consulta->classificacao_risco = $dadosValidados['classificacao_risco'];
        
        // Assumindo que a FK do recepcionista é 'idRecepcionistaFK'
        $consulta->idRecepcionistaFK = Auth::id(); // Pega o ID do recepcionista logado
        
        // (Se o recepcionista for ligado a uma unidade, descomente abaixo)
        // $consulta->idUnidadeFK = Auth::user()->idUnidadeFK; 

        $consulta->dataConsulta = now();
        $consulta->status_atendimento = 'AGUARDANDO_TRIAGEM';
        
        $consulta->save();

        // Redireciona de volta para o painel com msg de sucesso
        return redirect()->route('recepcionista.dashboard')
                         ->with('success', 'Paciente encaminhado para triagem!');
    }
}