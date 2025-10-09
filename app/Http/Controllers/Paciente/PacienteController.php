<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PacienteController extends Controller
{
    
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

        $pacientes = $query->orderBy('nomePaciente', 'asc')->get();

        return view('geral.pacientes', [
            'pacientes' => $pacientes
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
        // Log para debug
        Log::info('Update iniciado', [
            'id' => $id,
            'dados' => $request->all()
        ]);

        $paciente = Paciente::where('idPaciente', $id)->firstOrFail();
        
        $validatedData = $request->validate([
            'nomePaciente'      => 'required|string|min:2|max:255',
            'cpfPaciente'       => ['required', 'string', 'size:11', Rule::unique('tbPaciente', 'cpfPaciente')->ignore($paciente->idPaciente, 'idPaciente')],
            'dataNascPaciente'  => 'required|date',
            'cartaoSusPaciente' => ['nullable', 'string', 'max:20', Rule::unique('tbPaciente', 'cartaoSusPaciente')->ignore($paciente->idPaciente, 'idPaciente')],
            'generoPaciente'    => 'required|string|in:Masculino,Feminino,Outro',
            'statusPaciente'    => 'required|in:0,1',
        ]);

        // Converte statusPaciente para boolean
        $validatedData['statusPaciente'] = (bool) $validatedData['statusPaciente'];

        Log::info('Dados validados', $validatedData);

        // Atualiza o paciente
        $paciente->update($validatedData);

        Log::info('Paciente atualizado', $paciente->fresh()->toArray());

        return redirect()->route('admin.pacientes.index')
            ->with('success', 'Paciente atualizado com sucesso!');
    }
    
    // REMOVENDO A FUNÇÃO 'DESTROY' PARA ADOTAR A LÓGICA DE INATIVAR
    /*
    public function destroy($id)
    {
        $paciente = Paciente::where('idPaciente', $id)->firstOrFail();
        $paciente->delete();

        return redirect()->route('admin.pacientes.index')
            ->with('success', 'Paciente excluído com sucesso!');
    }
    */
    
    /**
     * Alterna o status do paciente entre Ativo e Inativo.
     * Esta função unifica a lógica de ativação e "exclusão".
     */
    public function toggleStatus($id)
    {
        $paciente = Paciente::where('idPaciente', $id)->firstOrFail();
        
        // Alterna entre true (ativo) e false (inativo)
        $paciente->statusPaciente = !$paciente->statusPaciente;
        $paciente->save();

        $acao = $paciente->statusPaciente ? 'ativado' : 'desativado';
        $mensagem = "O paciente foi {$acao} com sucesso!";

        return redirect()->route('admin.pacientes.index')
            ->with('success', $mensagem);
    }
}