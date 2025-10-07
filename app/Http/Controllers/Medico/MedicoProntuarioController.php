<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Prontuario;
use Illuminate\Support\Facades\Auth;

class MedicoProntuarioController extends Controller
{
    /**
     * Exibe a lista de pacientes com seus prontuários (tela principal)
     * View: medico.prontuarioMedico
     */
    public function index()
    {
        $pacientes = Paciente::orderBy('nomePaciente', 'asc')->get();
        
        return view('medico.prontuarioMedico', compact('pacientes'));
    }

    /**
     * Exibe o prontuário completo de um paciente (histórico de consultas)
     * Rota: /medico/prontuario/{id}
     * View: medico.prontuario_detalhe (ou a que você usar)
     */
    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        
        // Busca todas as consultas do paciente ordenadas por data (mais recente primeiro)
        $consultas = Prontuario::where('idPaciente', $id)
            ->orderBy('dataConsulta', 'desc')
            ->orderBy('horaConsulta', 'desc')
            ->get();
        
        return view('medico.prontuario_detalhe', compact('paciente', 'consultas'));
    }

    /**
     * Exibe o formulário para criar nova consulta
     * Rota: /medico/cadastrar-prontuario/{id}
     * View: medico.cadastrarProntuario
     */
    public function create($id)
    {
        $paciente = Paciente::findOrFail($id);
        
        // Verifica se o paciente está ativo
        if (!$paciente->statusPaciente) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Não é possível criar consulta para paciente inativo.');
        }
        
        return view('medico.cadastrarProntuario', compact('paciente'));
    }

    /**
     * Armazena uma nova consulta/prontuário
     * Rota: POST /medico/cadastrar-prontuario/{id}
     */
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'dataConsulta' => 'required|date',
            'horaConsulta' => 'required',
            'queixaPrincipal' => 'required|string|max:1000',
            'historiaDoenca' => 'nullable|string|max:2000',
            'exameFisico' => 'nullable|string|max:2000',
            'hipoteseDiagnostica' => 'nullable|string|max:1000',
            'conduta' => 'nullable|string|max:2000',
            'prescricao' => 'nullable|string|max:2000',
            'observacoes' => 'nullable|string|max:1000',
        ], [
            'dataConsulta.required' => 'A data da consulta é obrigatória.',
            'dataConsulta.date' => 'Data inválida.',
            'horaConsulta.required' => 'A hora da consulta é obrigatória.',
            'queixaPrincipal.required' => 'A queixa principal é obrigatória.',
            'queixaPrincipal.max' => 'A queixa principal não pode ter mais de 1000 caracteres.',
        ]);

        $paciente = Paciente::findOrFail($id);
        
        // Verifica se o paciente está ativo
        if (!$paciente->statusPaciente) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Não é possível criar consulta para paciente inativo.');
        }

        // Cria o prontuário/consulta
        $prontuario = new Prontuario();
        $prontuario->idPaciente = $id;
        $prontuario->idMedico = Auth::id(); // Ajuste se necessário: Auth::user()->idMedico
        $prontuario->dataConsulta = $validated['dataConsulta'];
        $prontuario->horaConsulta = $validated['horaConsulta'];
        $prontuario->queixaPrincipal = $validated['queixaPrincipal'];
        $prontuario->historiaDoenca = $validated['historiaDoenca'] ?? null;
        $prontuario->exameFisico = $validated['exameFisico'] ?? null;
        $prontuario->hipoteseDiagnostica = $validated['hipoteseDiagnostica'] ?? null;
        $prontuario->conduta = $validated['conduta'] ?? null;
        $prontuario->prescricao = $validated['prescricao'] ?? null;
        $prontuario->observacoes = $validated['observacoes'] ?? null;
        $prontuario->save();

        return redirect()
            ->route('medico.paciente.prontuario', $id)
            ->with('success', 'Consulta registrada com sucesso!');
    }

    /**
     * Exibe o formulário para editar uma consulta
     * Rota: /medico/prontuario/editar/{id}
     */
    public function edit($idProntuario)
    {
        $prontuario = Prontuario::findOrFail($idProntuario);
        $paciente = Paciente::findOrFail($prontuario->idPaciente);
        
        // Verifica se o médico logado é o dono do prontuário
        if ($prontuario->idMedico !== Auth::id()) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Você não tem permissão para editar este prontuário.');
        }
        
        return view('medico.editarProntuario', compact('prontuario', 'paciente'));
    }

    /**
     * Atualiza uma consulta existente
     * Rota: PUT /medico/prontuario/atualizar/{id}
     */
    public function update(Request $request, $idProntuario)
    {
        $prontuario = Prontuario::findOrFail($idProntuario);
        
        // Verifica se o médico logado é o dono do prontuário
        if ($prontuario->idMedico !== Auth::id()) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Você não tem permissão para editar este prontuário.');
        }

        $validated = $request->validate([
            'dataConsulta' => 'required|date',
            'horaConsulta' => 'required',
            'queixaPrincipal' => 'required|string|max:1000',
            'historiaDoenca' => 'nullable|string|max:2000',
            'exameFisico' => 'nullable|string|max:2000',
            'hipoteseDiagnostica' => 'nullable|string|max:1000',
            'conduta' => 'nullable|string|max:2000',
            'prescricao' => 'nullable|string|max:2000',
            'observacoes' => 'nullable|string|max:1000',
        ], [
            'dataConsulta.required' => 'A data da consulta é obrigatória.',
            'horaConsulta.required' => 'A hora da consulta é obrigatória.',
            'queixaPrincipal.required' => 'A queixa principal é obrigatória.',
        ]);

        $prontuario->update($validated);

        return redirect()
            ->route('medico.paciente.prontuario', $prontuario->idPaciente)
            ->with('success', 'Consulta atualizada com sucesso!');
    }
}