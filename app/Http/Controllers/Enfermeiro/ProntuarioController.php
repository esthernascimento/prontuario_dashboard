<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Unidade;
use App\Models\AnotacaoEnfermagem;
use App\Models\Enfermeiro;
use App\Models\Consulta;
use App\Models\Alergia; // Importar o model Alergia
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Importar DB para a transaction

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
                            ->firstOrFail(); // Garante que a consulta exista

        $enfermeiro = Enfermeiro::where('id_usuario', Auth::id())->first();
        if (!$enfermeiro) {
            // Lidar com enfermeiro não encontrado (ex: logar e redirecionar)
             return redirect()->route('enfermeiro.prontuario')->with('error', 'Enfermeiro não encontrado.');
        }

        return view('enfermeiro.cadastrarProntuarioEnfermeiro', compact('paciente', 'unidades', 'consulta', 'enfermeiro'));
    }

    
    public function store(Request $request, $pacienteId)
    {

        $validatedData = $request->validate([
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
            
            // --- CORREÇÃO: Validação voltando para array ---
            'alergias' => 'nullable|array', 
            'alergias.*' => 'nullable|string', // Valida cada item dentro do array
            'medicacoes_ministradas' => 'nullable|array', 
            'medicacoes_ministradas.*' => 'nullable|string', // Valida cada item
        ]);


        $enfermeiro = Enfermeiro::where('id_usuario', Auth::id())->firstOrFail();
        
        // --- CORREÇÃO: Reintroduzindo o implode() ---
        $alergias_str = !empty($validatedData['alergias']) ? implode(', ', $validatedData['alergias']) : null;
        $medicacoes_str = !empty($validatedData['medicacoes_ministradas']) ? implode(', ', $validatedData['medicacoes_ministradas']) : null;
        
        $anotacao = new AnotacaoEnfermagem();
        $anotacao->idPacienteFK = $pacienteId;
        $anotacao->idEnfermeiroFK = $enfermeiro->idEnfermeiroPK;
        $anotacao->tipo_registro = $validatedData['tipo_registro'];
        $anotacao->data_hora = $validatedData['data_hora'];
        $anotacao->descricao = $validatedData['descricao'];
        $anotacao->unidade_atendimento = $validatedData['unidade_atendimento'];
        $anotacao->pressao_arterial = $validatedData['pressao_arterial'];
        $anotacao->temperatura = $validatedData['temperatura'];
        $anotacao->frequencia_cardiaca = $validatedData['frequencia_cardiaca'];
        $anotacao->frequencia_respiratoria = $validatedData['frequencia_respiratoria'];
        $anotacao->saturacao = $validatedData['saturacao'] ? str_replace('%', '', $validatedData['saturacao']) : null;
        $anotacao->dor = $validatedData['dor'];
        
        // --- CORREÇÃO: Atribui as strings convertidas ---
        $anotacao->alergias = $alergias_str;
        $anotacao->medicacoes_ministradas = $medicacoes_str;
        
        // Persistência atômica da anotação, atualização da consulta e inserção de alergias
        DB::transaction(function () use ($anotacao, $validatedData, $enfermeiro) {
            $anotacao->save(); // Salva a anotação primeiro

            // Atualiza a consulta
            $consulta = Consulta::findOrFail($validatedData['idConsulta']);
            $consulta->status_atendimento = 'AGUARDANDO_CONSULTA';
            $consulta->classificacao_risco = $validatedData['classificacao_risco'];
            $consulta->idEnfermeiroFK = $enfermeiro->idEnfermeiroPK;
            
            if (!$consulta->idUnidadeFK && !empty($validatedData['unidade_atendimento'])) {
                $consulta->idUnidadeFK = $validatedData['unidade_atendimento'];
            }
            $consulta->save();

            // Inserir alergias do paciente com base nas anotações, se informado
            if (!empty($anotacao->alergias)) {
                $nomes = collect(explode(',', $anotacao->alergias)) // Separa a string por vírgula
                    ->map(fn($v) => trim($v)) // Limpa espaços
                    ->filter(); // Remove vazios
                
                foreach ($nomes as $nome) {
                    
                    // Bug 'nomeAlergia' já estava corrigido
                    Alergia::firstOrCreate([
                        'idPacienteFK' => $anotacao->idPacienteFK,
                        'descAlergia' => $nome,
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

