<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Prontuario;
use App\Models\Consulta;
use App\Models\Medico;
use Illuminate\Support\Facades\Auth;
use App\Models\Unidade;

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
     * Rota: /medico/visualizar-prontuario/{id}
     * View: medico.visualizarProntuario
     */
    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        
        // Busca o prontuário do paciente
        $prontuario = Prontuario::where('idPacienteFK', $paciente->idPaciente)->first();
        
        if (!$prontuario) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Este paciente ainda não possui prontuário.');
        }
        
        // Busca todas as consultas do prontuário ordenadas por data (mais recente primeiro)
        $consultas = Consulta::where('idProntuarioFK', $prontuario->idProntuarioPK)
            ->orderBy('dataConsulta', 'desc')
            ->get();
        
        return view('medico.visualizarProntuario', compact('paciente', 'prontuario', 'consultas'));
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
        
        // Busca os dados do médico logado
        $medico = Auth::user()->medico;
        
        // Verifica se o paciente tem prontuário, se não, cria um
        $prontuario = Prontuario::firstOrCreate(
            ['idPacienteFK' => $paciente->idPaciente],
            ['dataAbertura' => now()->toDateString()]
        );
        
        return view('medico.cadastrarProntuario', compact('paciente', 'medico'));
    }

    /**
     * Armazena uma nova consulta/prontuário
     * Rota: POST /medico/cadastrar-prontuario/{id}
     */
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'dataConsulta' => 'required|date',
            'unidade' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string|max:2000',
            'examesSolicitados' => 'nullable|string|max:2000',
            'medicamentosPrescritos' => 'nullable|string|max:2000',
        ], [
            'dataConsulta.required' => 'A data da consulta é obrigatória.',
            'dataConsulta.date' => 'Data inválida.',
        ]);

        $paciente = Paciente::findOrFail($id);
        
        // Verifica se o paciente está ativo
        if (!$paciente->statusPaciente) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Não é possível criar consulta para paciente inativo.');
        }

        // Busca o médico logado
        $medico = Auth::user()->medico;
        
        // Busca ou cria o prontuário do paciente
        $prontuario = Prontuario::firstOrCreate(
            ['idPacienteFK' => $paciente->idPaciente],
            ['dataAbertura' => now()->toDateString()]
        );

        // Busca o ID da unidade com base no nome fornecido
        $unidade = Unidade::where('nomeUnidade', $validated['unidade'])->first();
        
        // Cria a consulta
        $consulta = new Consulta();
        $consulta->idProntuarioFK = $prontuario->idProntuarioPK;
        $consulta->idMedicoFK = $medico->idMedicoPK;
        
        // Atribuimos os valores às colunas que agora existem na sua tabela
        $consulta->nomeMedico = $medico->nomeMedico;
        $consulta->crmMedico = $medico->crmMedico;
        $consulta->idUnidadeFK = $unidade->idUnidadePK ?? null; 
        $consulta->dataConsulta = $validated['dataConsulta'];
        $consulta->unidade = $validated['unidade'] ?? null;
        $consulta->observacoes = $validated['observacoes'] ?? null;
        $consulta->examesSolicitados = $validated['examesSolicitados'] ?? null;
        $consulta->medicamentosPrescritos = $validated['medicamentosPrescritos'] ?? null;

        $consulta->save();

        
        return redirect()
            ->route('medico.paciente.prontuario', $id)
            ->with('success', 'Consulta registrada com sucesso!');
    }

    /**
     * Exibe o formulário para editar uma consulta
     * Rota: /medico/prontuario/editar/{id}
     */
    public function edit($idConsulta)
    {
        $consulta = Consulta::findOrFail($idConsulta);
        $prontuario = $consulta->prontuario;
        $paciente = Paciente::where('idPaciente', $prontuario->idPacienteFK)->firstOrFail();
        $medico = Auth::user()->medico;
        
        // Verifica se o médico logado é o dono da consulta
        if ($consulta->idMedicoFK !== $medico->idMedicoPK) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Você não tem permissão para editar esta consulta.');
        }
        
        return view('medico.editarProntuario', compact('consulta', 'paciente', 'medico'));
    }

    /**
     * Atualiza uma consulta existente
     * Rota: PUT /medico/prontuario/atualizar/{id}
     */
    public function update(Request $request, $idConsulta)
    {
        $consulta = Consulta::findOrFail($idConsulta);
        $medico = Auth::user()->medico;
        
        // Verifica se o médico logado é o dono da consulta
        if ($consulta->idMedicoFK !== $medico->idMedicoPK) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Você não tem permissão para editar esta consulta.');
        }

        $validated = $request->validate([
            'dataConsulta' => 'required|date',
            'unidade' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string|max:2000',
            'examesSolicitados' => 'nullable|string|max:2000',
            'medicamentosPrescritos' => 'nullable|string|max:2000',
        ], [
            'dataConsulta.required' => 'A data da consulta é obrigatória.',
            'dataConsulta.date' => 'Data inválida.',
        ]);

        // Busca o ID da unidade com base no nome fornecido
        $unidade = Unidade::where('nomeUnidade', $validated['unidade'])->first();
        
        $consulta->dataConsulta = $validated['dataConsulta'];
        $consulta->idUnidadeFK = $unidade->idUnidadePK ?? null;
        $consulta->unidade = $validated['unidade'] ?? null;
        $consulta->observacoes = $validated['observacoes'] ?? null;
        $consulta->examesSolicitados = $validated['examesSolicitados'] ?? null;
        $consulta->medicamentosPrescritos = $validated['medicamentosPrescritos'] ?? null;
        $consulta->save();

        $prontuario = $consulta->prontuario;
        $paciente = Paciente::where('idPaciente', $prontuario->idPacienteFK)->first();

        return redirect()
            ->route('medico.paciente.prontuario', $paciente->idPaciente)
            ->with('success', 'Consulta atualizada com sucesso!');
    }

    /**
     * Remove uma consulta (soft delete)
     * Rota: DELETE /medico/prontuario/deletar/{id}
     */
    public function destroy($idConsulta)
    {
        $consulta = Consulta::findOrFail($idConsulta);
        $medico = Auth::user()->medico;
        
        // Verifica se o médico logado é o dono da consulta
        if ($consulta->idMedicoFK !== $medico->idMedicoPK) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Você não tem permissão para excluir esta consulta.');
        }

        $prontuario = $consulta->prontuario;
        $paciente = Paciente::where('idPaciente', $prontuario->idPacienteFK)->first();
        
        $consulta->delete();

        return redirect()
            ->route('medico.paciente.prontuario', $paciente->idPaciente)
            ->with('success', 'Consulta excluída com sucesso!');
    }
}