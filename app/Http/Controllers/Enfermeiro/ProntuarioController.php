<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Unidade;
use App\Models\AnotacaoEnfermagem;
use App\Models\Enfermeiro;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;

class ProntuarioController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::whereHas('consultas', function ($query) {
            $query->where('status_atendimento', 'AGUARDANDO_TRIAGEM');
        })
        ->orderBy('nomePaciente', 'asc')
        ->get();

        return view('enfermeiro.prontuarioEnfermeiro', compact('pacientes'));
    }

    public function create($pacienteId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        $unidades = Unidade::orderBy('nomeUnidade')->get();

        $consulta = Consulta::where('idPacienteFK', $pacienteId)
                            ->where('status_atendimento', 'AGUARDANDO_TRIAGEM')
                            ->orderBy('dataConsulta', 'desc')
                            ->firstOrFail();

        $enfermeiro = Enfermeiro::where('id_usuario', Auth::id())->first();

        return view('enfermeiro.cadastrarProntuarioEnfermeiro', compact('paciente', 'unidades', 'consulta', 'enfermeiro'));
    }

   
    public function store(Request $request, $pacienteId)
    {

        $request->validate([
            'idConsulta' => 'required|exists:tbConsulta,idConsultaPK',
            'classificacao_risco' => 'required|string', 
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
            'alergias' => 'nullable|array',
            'alergias.*' => 'nullable|string',
            'medicacoes_ministradas' => 'nullable|array',
            'medicacoes_ministradas.*' => 'nullable|string',
        ]);


        $enfermeiro = Enfermeiro::where('id_usuario', Auth::id())->firstOrFail();
        
 
        $alergias_str = $request->alergias ? implode(', ', $request->alergias) : null;
        $medicacoes_str = $request->medicacoes_ministradas ? implode(', ', $request->medicacoes_ministradas) : null;
        
        $anotacao = new AnotacaoEnfermagem();
        $anotacao->idPacienteFK = $pacienteId;
        $anotacao->idEnfermeiroFK = $enfermeiro->idEnfermeiroPK;
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
        
       
        $anotacao->alergias = $alergias_str;
        $anotacao->medicacoes_ministradas = $medicacoes_str;
        
        // Persistência atômica da anotação, atualização da consulta e inserção de alergias
        \DB::transaction(function () use ($anotacao, $request, $enfermeiro) {
            $anotacao->save();

            $consulta = Consulta::findOrFail($request->idConsulta);
            $consulta->status_atendimento = 'AGUARDANDO_CONSULTA'; 
            $consulta->classificacao_risco = $request->classificacao_risco; 
            $consulta->idEnfermeiroFK = $enfermeiro->idEnfermeiroPK;
            // Captura e persiste a unidade escolhida, caso ainda não tenha sido definida
            if (!$consulta->idUnidadeFK && $request->filled('unidade_atendimento')) {
                $consulta->idUnidadeFK = $request->unidade_atendimento;
            }
            $consulta->save();

            // Inserir alergias do paciente com base nas anotações, se informado
            if (!empty($anotacao->alergias)) {
                $nomes = collect(explode(',', $anotacao->alergias))
                    ->map(fn($v) => trim($v))
                    ->filter();
                foreach ($nomes as $nome) {
                    \App\Models\Alergia::firstOrCreate([
                        'idPacienteFK' => $anotacao->idPacienteFK,
                        'descAlergia' => $nome,
                    ], [
                        'nomeAlergia' => $nome,
                    ]);
                }
            }
        });
   
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