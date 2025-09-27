<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Enfermeiro;
use App\Models\Usuario;

class EnfermeiroController extends Controller
{
    // Listagem de enfermeiros
    public function index()
    {
        $enfermeiros = Enfermeiro::with('usuario')->get();
        return view('admin.manutencaoEnfermeiro', compact('enfermeiros'));
    }

    // Formulário de cadastro
    public function create()
    {
        return view('admin.cadastroEnfermeiro');
    }

    // Salvar cadastro
    public function store(Request $request)
    {
        $request->validate([
            'nomeEnfermeiro' => 'required|string|max:255',
            'emailEnfermeiro' => [
                'required',
                'email',
                Rule::unique((new Enfermeiro)->getTable(), 'emailEnfermeiro'),
            ],
            'corenEnfermeiro' => 'required|string|max:50',
            'especialidadeEnfermeiro' => 'nullable|string|max:100',
            'genero' => 'required|string|max:20',
        ]);

        $usuario = Usuario::create([
            'nomeUsuario' => $request->nomeEnfermeiro,
            'emailUsuario' => $request->emailEnfermeiro,
            'senhaUsuario' => bcrypt('12345678'),
            'statusAtivoUsuario' => true,
        ]);

        Enfermeiro::create([
            'nomeEnfermeiro' => $request->nomeEnfermeiro,
            'emailEnfermeiro' => $request->emailEnfermeiro,
            'corenEnfermeiro' => $request->corenEnfermeiro,
            'especialidadeEnfermeiro' => $request->especialidadeEnfermeiro,
            'genero' => $request->genero,
            'id_usuario' => $usuario->idUsuarioPK,
        ]);

        return response()->json(['message' => 'Enfermeiro pré-cadastrado com sucesso!']);
    }

    public function editar($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);
        return view('admin.editarEnfermeiro', compact('enfermeiro'));
    }

    public function update(Request $request, $id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);

        $request->validate([
            'nomeEnfermeiro' => 'required|string|max:255',
            'emailEnfermeiro' => [
                'required',
                'email',
                Rule::unique((new Enfermeiro)->getTable(), 'emailEnfermeiro')->ignore($id, 'idEnfermeiroPK'),
            ],
            'corenEnfermeiro' => 'required|string|max:50',
            'especialidadeEnfermeiro' => 'nullable|string|max:100',
            'genero' => 'required|string|max:20',

            'nomeUsuario' => 'required|string|max:255',
            'emailUsuario' => [
                'required',
                'email',
                Rule::unique((new Usuario)->getTable(), 'emailUsuario')
                    ->ignore($enfermeiro->usuario->idUsuarioPK, 'idUsuarioPK'),
            ],
        ]);

        $enfermeiro->update([
            'nomeEnfermeiro' => $request->nomeEnfermeiro,
            'emailEnfermeiro' => $request->emailEnfermeiro,
            'corenEnfermeiro' => $request->corenEnfermeiro,
            'especialidadeEnfermeiro' => $request->especialidadeEnfermeiro,
            'genero' => $request->genero,
        ]);

        $enfermeiro->usuario->update([
            'nomeUsuario' => $request->nomeUsuario,
            'emailUsuario' => $request->emailUsuario,
        ]);

        return redirect()->route('admin.manutencaoEnfermeiro')->with('success', 'Dados atualizados com sucesso.');
    }

    public function show($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);
        return view('admin.visualizarEnfermeiro', compact('enfermeiro'));
    }

    public function toggleStatus($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);

        if ($enfermeiro->usuario) {
            $enfermeiro->usuario->statusAtivoUsuario = !$enfermeiro->usuario->statusAtivoUsuario;
            $enfermeiro->usuario->save();
        }

        return back()->with('success', 'Status do enfermeiro atualizado.');
    }

    public function confirmarExclusao($id)
    {
        $enfermeiro = Enfermeiro::findOrFail($id);
        return view('admin.desativarEnfermeiro', compact('enfermeiro'));
    }

    public function excluir($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);

        if ($enfermeiro->usuario) {
            $enfermeiro->usuario->delete();
        }

        $enfermeiro->delete();

        return redirect()->route('admin.manutencaoEnfermeiro')->with('success', 'Enfermeiro e usuário excluídos com sucesso.');
    }
}
