<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente; // Modelo para o paciente
use App\Models\AnotacaoEnfermagem; // Assumindo que você terá um modelo para as anotações
use Carbon\Carbon; // Para formatação de data (se necessário)
use Illuminate\Support\Facades\Auth; // Para obter o ID do enfermeiro logado

class ProntuarioController extends Controller
{

    public function index()
    {

        $pacientes = Paciente::orderBy('nomePaciente')->paginate(15);
        
        return view('enfermeiro.prontuarioEnfermeiro', compact('pacientes'));
    }

    public function createAnotacao($id) 
{
        $paciente = Paciente::findOrFail($id); 

        return view('enfermeiro.cadastrarProntuarioEnfermeiro', compact('paciente')); 

    }

    public function show($id)
    {
        try {

        $paciente = Paciente::findOrFail($id);

        $anotacoes = AnotacaoEnfermagem::where('idPacienteFK', $paciente->idPaciente)
                                       ->orderBy('data_hora', 'desc') // Ordena pelas mais recentes
                                       ->get();

        return view('enfermeiro.visualizarProntuarioEnfermeiro', compact('paciente', 'anotacoes'));

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Se o Paciente não for encontrado
        return redirect()->route('enfermeiro.prontuario.index')->with('error', 'Paciente não encontrado.');
    }
    }

    public function anotacao($id)
    {

    $paciente = Paciente::findOrFail($id); 


    return view('enfermeiro.visualizarProntuarioEnfermeiro', compact('paciente')); 
    }

public function storeAnotacao(Request $request, $id)
{

    try {
        
        // Mantenha assim:
        return redirect()
            ->route('enfermeiro.prontuario.show', $id) // Usa o $id da URL, que é o idPaciente
            ->with('success', 'Anotação de enfermagem registrada com sucesso!');

    } catch (\Exception $e) {

        return redirect()
            ->route('enfermeiro.prontuario.show', $id) // Tenta redirecionar, mesmo com erro
            ->with('error', 'Erro DETALHADO: Falha ao salvar no banco de dados. Verifique o Model e o DB. Detalhe: ' . $e->getMessage());


    }
}
}
