<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    /**
     * Exibe a lista de pacientes com filtros para o painel administrativo.
     */
    public function index(Request $request)
    {
        $query = Paciente::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nomePaciente', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('cpfPaciente', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('cartaoSusPaciente', 'LIKE', "%{$searchTerm}%");
            });
        }

        $pacientes = $query->orderBy('nomePaciente', 'asc')->paginate(10);

        return view('geral.pacientes', [
            'pacientes' => $pacientes->appends($request->query())
        ]);
    }

    /**
     * Mostra o formulário para criar um novo paciente.
     */
    public function create()
    {
        return view('admin.cadastroPaciente');
    }

    /**
     * Salva um novo paciente no banco de dados.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomePaciente'      => 'required|string|min:2',
            'cpfPaciente'       => ['required', 'string', 'size:11', Rule::unique('tbPaciente', 'cpfPaciente')],
            'dataNascPaciente'  => 'required|date',
            'cartaoSusPaciente' => ['nullable', 'string', 'max:20', Rule::unique('tbPaciente', 'cartaoSusPaciente')],
            'generoPaciente'    => 'required|string',
            'statusPaciente'    => 'nullable|boolean',
        ]);

        // Se não vier statusPaciente, define como true (ativo)
        if (!isset($validatedData['statusPaciente'])) {
            $validatedData['statusPaciente'] = true;
        }

        Paciente::create($validatedData);

        return redirect()->route('admin.pacientes.index')->with('success', 'Paciente cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um paciente existente.
     * CORRIGIDO: usa idPaciente (a chave real do Model)
     */
    public function edit($id)
    {
        $paciente = Paciente::where('idPaciente', $id)->firstOrFail();
        return view('admin.editarPaciente', compact('paciente'));
    }

    /**
     * Atualiza um paciente existente no banco de dados.
     * CORRIGIDO: usa idPaciente
     */
    public function update(Request $request, $id)
    {
        $paciente = Paciente::where('idPaciente', $id)->firstOrFail();
        
        $validatedData = $request->validate([
            'nomePaciente'      => 'required|string|min:2',
            'cpfPaciente'       => ['required', 'string', 'size:11', Rule::unique('tbPaciente', 'cpfPaciente')->ignore($paciente->idPaciente, 'idPaciente')],
            'dataNascPaciente'  => 'required|date',
            'cartaoSusPaciente' => ['nullable', 'string', 'max:20', Rule::unique('tbPaciente', 'cartaoSusPaciente')->ignore($paciente->idPaciente, 'idPaciente')],
            'generoPaciente'    => 'required|string',
            'statusPaciente'    => 'required|boolean',
        ]);

        $paciente->update($validatedData);

        return redirect()->route('admin.pacientes.index')->with('success', 'Paciente atualizado com sucesso!');
    }

    /**
     * Exclusão (Soft Delete)
     * CORRIGIDO: usa idPaciente
     */
    public function destroy($id)
    {
        $paciente = Paciente::where('idPaciente', $id)->firstOrFail();
        $paciente->delete();

        return redirect()->route('admin.pacientes.index')
            ->with('success', 'Paciente excluído com sucesso!');
    }
    
    /**
     * Toggle Status (Ativo/Inativo)
     * CORRIGIDO: usa idPaciente e trabalha com boolean
     */
    public function toggleStatus($id)
    {
        $paciente = Paciente::where('idPaciente', $id)->firstOrFail();
        
        // Alterna entre true (ativo) e false (inativo)
        $paciente->statusPaciente = !$paciente->statusPaciente;
        $paciente->save();

        $statusTexto = $paciente->statusPaciente ? 'Ativo' : 'Inativo';

        return redirect()->route('admin.pacientes.index')
            ->with('success', 'Status do paciente alterado para ' . $statusTexto . ' com sucesso!');
    }
}