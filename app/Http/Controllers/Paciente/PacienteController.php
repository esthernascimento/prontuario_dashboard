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

        if ($request->filled('gender')) {
            $query->where('generoPaciente', $request->input('gender'));
        }

        if ($request->filled('age')) {
            $ageRange = $request->input('age');
            $today = now();
            if ($ageRange == 'crianca') {
                $query->whereDate('dataNascPaciente', '>=', $today->copy()->subYears(12));
            } elseif ($ageRange == 'adolescente') {
                $query->whereBetween('dataNascPaciente', [$today->copy()->subYears(18)->addDay(), $today->copy()->subYears(13)->addDay()]);
            } elseif ($ageRange == 'adulto') {
                $query->whereBetween('dataNascPaciente', [$today->copy()->subYears(60)->addDay(), $today->copy()->subYears(18)]);
            } elseif ($ageRange == 'idoso') {
                $query->whereDate('dataNascPaciente', '<=', $today->copy()->subYears(60));
            }
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
     * =======================================================================
     * --- NOVO MÉTODO ADICIONADO ---
     * Salva um novo paciente no banco de dados.
     * =======================================================================
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomePaciente'      => 'required|string|min:2',
            'cpfPaciente'       => ['required', 'string', 'size:11', Rule::unique('tbPaciente', 'cpfPaciente')],
            'dataNascPaciente'  => 'required|date',
            'cartaoSusPaciente' => ['nullable', 'string', 'max:20', Rule::unique('tbPaciente', 'cartaoSusPaciente')],
            // Adicione outras regras de validação conforme necessário
        ]);

        Paciente::create($validatedData);

        return redirect()->route('admin.pacientes.index')->with('success', 'Paciente cadastrado com sucesso!');
    }


    /**
     * Mostra o formulário para editar um paciente existente.
     */
    public function edit(Paciente $paciente)
    {
        return view('admin.editarPaciente', compact('paciente'));
    }

    /**
     * =======================================================================
     * --- NOVO MÉTODO ADICIONADO ---
     * Atualiza um paciente existente no banco de dados.
     * =======================================================================
     */
    public function update(Request $request, Paciente $paciente)
    {
        $validatedData = $request->validate([
            'nomePaciente'      => 'required|string|min:2',
            'cpfPaciente'       => ['required', 'string', 'size:11', Rule::unique('tbPaciente', 'cpfPaciente')->ignore($paciente->idPacientePK, 'idPacientePK')],
            'dataNascPaciente'  => 'required|date',
            'cartaoSusPaciente' => ['nullable', 'string', 'max:20', Rule::unique('tbPaciente', 'cartaoSusPaciente')->ignore($paciente->idPacientePK, 'idPacientePK')],
            // Adicione outras regras de validação conforme necessário
        ]);

        $paciente->update($validatedData);

        return redirect()->route('admin.pacientes.index')->with('success', 'Paciente atualizado com sucesso!');
    }

    // Você precisará adicionar o método destroy() aqui depois.
}

