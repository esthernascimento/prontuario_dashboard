<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Unidade;
use App\Models\AnotacaoEnfermagem;
use App\Models\Enfermeiro;
use App\Models\Consulta; // --- ADICIONADO --- Precisamos do modelo Consulta
use Illuminate\Support\Facades\Auth;

class ProntuarioController extends Controller
{
    public function index()
    {
        // Esta lógica está perfeita. Busca os pacientes que têm uma consulta aguardando triagem.
        $pacientes = Paciente::whereHas('consultas', function ($query) {
            $query->where('status_atendimento', 'AGUARDANDO_TRIAGEM');
        })
        ->orderBy('nomePaciente', 'asc')
        ->get();

        return view('enfermeiro.prontuarioEnfermeiro', compact('pacientes'));
    }

    /**
     * Mostra o formulário de triagem (anotação)
     */
    public function create($pacienteId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        $unidades = Unidade::orderBy('nomeUnidade')->get();

        // ================================================================
        // --- ADICIONADO ---
        // Precisamos encontrar a consulta que o recepcionista abriu.
        // Se houver várias, pega a mais recente que ainda está aguardando.
        $consulta = Consulta::where('idPacienteFK', $pacienteId)
                            ->where('status_atendimento', 'AGUARDANDO_TRIAGEM')
                            ->orderBy('dataConsulta', 'desc')
                            ->firstOrFail(); // Falha se não encontrar a consulta (o que é bom)
        // ================================================================

        // Passa a $consulta para a view
        return view('enfermeiro.cadastrarProntuarioEnfermeiro', compact('paciente', 'unidades', 'consulta'));
    }

    /**
     * Salva a triagem (Anotação de Enfermagem) E atualiza a Consulta.
     */
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            // --- ADICIONADO ---
            'idConsulta' => 'required|exists:tbConsulta,idConsultaPK',
            'classificacao_risco' => 'required|string',
            // ------------------

            'tipo_registro' => 'required|string',
            'data_hora' => 'required|date',
            'descricao' => 'required|string',
            'unidade_atendimento' => 'required|exists:tbUnidade,idUnidadePK',
            'pressao_arterial' => 'nullable|string',
            'temperatura' => 'nullable|string',
            'frequencia_cardiaca' => 'nullable|string',
            'frequencia_respiratoria' => 'nullable|string',
            'saturacao' => 'nullable|string',
            'dor' => 'nullable|integer|min:0|max:10',
            'alergias' => 'nullable|string',
            'medicacoes_ministradas' => 'nullable|string',
        ]);

        $enfermeiro = Enfermeiro::where('id_usuario', Auth::id())->firstOrFail();

        // 1. Salva a Anotação de Enfermagem (seu código original)
        $anotacao = new AnotacaoEnfermagem();
        $anotacao->idPacienteFK = $pacienteId;
        $anotacao->idEnfermeiroFK = $enfermeiro->idEnfermeiroPK; // pega o id correto
        $anotacao->tipo_registro = $request->tipo_registro;
        $anotacao->data_hora = $request->data_hora;
        $anotacao->descricao = $request->descricao;
        $anotacao->unidade_atendimento = $request->unidade_atendimento;
        $anotacao->pressao_arterial = $request->pressao_arterial;
        $anotacao->temperatura = $request->temperatura;
        $anotacao->frequencia_cardiaca = $request->frequencia_cardiaca;
        $anotacao->frequencia_respiratoria = $request->frequencia_respiratoria;
        $anotacao->saturacao = $request->saturacao ? str_replace('%', '', $request->saturacao) : null;
        $anotacao->dor = $request->dor;
        $anotacao->alergias = $request->alergias;
        $anotacao->medicacoes_ministradas = $request->medicacoes_ministradas;
        $anotacao->save();

     
      
        $consulta = Consulta::findOrFail($request->idConsulta);
        $consulta->status_atendimento = 'AGUARDANDO_CONSULTA'; 
        $consulta->classificacao_risco = $request->classificacao_risco; 
        $consulta->idEnfermeiroFK = $enfermeiro->idEnfermeiroPK;
        $consulta->save();
     

        return redirect()->route('enfermeiro.prontuario')
            ->with('success', 'Triagem realizada! Paciente encaminhado para consulta.');
    }

    public function show($pacienteId)
    {
        $paciente = Paciente::findOrFail($pacienteId);

        $anotacoes = AnotacaoEnfermagem::where('idPacienteFK', $pacienteId)
            ->orderBy('data_hora', 'desc')
            ->get();

        return view('enfermeiro.visualizarProntuarioEnfermeiro', compact('paciente', 'anotacoes'));
    }
}
