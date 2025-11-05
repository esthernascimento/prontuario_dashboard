<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnotacaoEnfermagem;
use App\Models\Paciente;
use App\Models\Enfermeiro;
use App\Models\Unidade;

class AnotacaoEnfermagemController extends Controller
{
    // Mostra o formulário de cadastro
    public function create()
    {
        $pacientes = Paciente::orderBy('nomePaciente')->get();
        $enfermeiros = Enfermeiro::orderBy('nomeEnfermeiro')->get();
        $unidades = Unidade::orderBy('nomeUnidade')->get();

        return view('admin.cadastroAnotacao', compact('pacientes', 'enfermeiros', 'unidades'));
    }

    // Salva a anotação no banco
    public function store(Request $request)
    {
        $request->validate([
            'idPacienteFK' => 'required|exists:tbPaciente,idPaciente',
            'idEnfermeiroFK' => 'required|exists:tbEnfermeiro,idEnfermeiroPK',
            'data_hora' => 'required|date',
            'tipo_registro' => 'required|string|max:50',
            'unidade_atendimento' => 'required|exists:tbUnidade,idUnidadePK',
            'descricao' => 'required|string',
            'temperatura' => 'nullable|numeric',
            'pressao_arterial' => 'nullable|string|max:20',
            'frequencia_cardiaca' => 'nullable|integer',
            'saturacao' => 'nullable|integer',
            'frequencia_respiratoria' => 'nullable|integer',
            'dor' => 'nullable|string|max:100',
            'alergias' => 'nullable|string|max:255',
            'medicacoes_ministradas' => 'nullable|string|max:255',
        ]);

        $anotacao = AnotacaoEnfermagem::create($request->all());

        return redirect()->back()->with('success', 'Anotação cadastrada com sucesso!');
    }
}
