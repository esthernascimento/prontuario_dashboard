<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $request->validate([
            'nomeEnfermeiro' => 'required|string|max:255',
            'emailEnfermeiro' => 'required|email|unique:tbenfermeiro,emailEnfermeiro', 
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

    // Formulário de edição
    public function editar($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);
        return view('admin.editarEnfermeiro', compact('enfermeiro'));
    }

    // Atualizar dados
    public function update(Request $request, $id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);

        $request->validate([
            'nomeEnfermeiro' => 'required|string|max:255',
            'emailEnfermeiro' => 'required|email|unique:tbenfermeiro,emailEnfermeiro,' . $id . ',idEnfermeiroPK', // ✅ Nome da tabela corrigido
            'corenEnfermeiro' => 'required|string|max:50',
            'especialidadeEnfermeiro' => 'nullable|string|max:100',
            'genero' => 'required|string|max:20',
            'nomeUsuario' => 'required|string|max:255',
            'emailUsuario' => 'required|email|unique:tbusuario,emailUsuario,' . $enfermeiro->usuario->idUsuarioPK . ',idUsuarioPK', // ✅ Nome da tabela corrigido
        ]);

        // Atualiza enfermeiro
        $enfermeiro->update([
            'nomeEnfermeiro' => $request->nomeEnfermeiro,
            'emailEnfermeiro' => $request->emailEnfermeiro,
            'corenEnfermeiro' => $request->corenEnfermeiro,
            'especialidadeEnfermeiro' => $request->especialidadeEnfermeiro,
            'genero' => $request->genero,
        ]);

        // Atualiza usuário vinculado
        $enfermeiro->usuario->update([
            'nomeUsuario' => $request->nomeUsuario,
            'emailUsuario' => $request->emailUsuario,
        ]);

        return redirect()->route('admin.manutencaoEnfermeiro')->with('success', 'Dados atualizados com sucesso.');
    }

    // Visualizar enfermeiro
    public function show($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);
        return view('admin.visualizarEnfermeiro', compact('enfermeiro'));
    }

    // Ativar/desativar status do usuário
    public function toggleStatus($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);

        if ($enfermeiro->usuario) {
            $enfermeiro->usuario->statusAtivoUsuario = !$enfermeiro->usuario->statusAtivoUsuario;
            $enfermeiro->usuario->save();
        }

        return back()->with('success', 'Status do enfermeiro atualizado.');
    }

    // Confirmar exclusão
    public function confirmarExclusao($id)
    {
        $enfermeiro = Enfermeiro::findOrFail($id);
        return view('admin.desativarEnfermeiro', compact('enfermeiro'));
    }

    // Excluir enfermeiro e usuário
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