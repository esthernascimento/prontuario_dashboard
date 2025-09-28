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

    // MÉTODO UPDATE (EDITADO PARA MODAL DE SUCESSO)
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

        // ADICIONANDO A FLAG 'updated' PARA DISPARAR O MODAL DE SUCESSO NO BLADE
        return redirect()->route('admin.manutencaoEnfermeiro')->with([
            'success' => 'Dados atualizados com sucesso.',
            'updated' => true // Flag para edição/atualização
        ]);
    }

    public function show($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);
        return view('admin.visualizarEnfermeiro', compact('enfermeiro'));
    }

    // MÉTODO TOGGLESTATUS (EDITADO PARA MODAL DE SUCESSO)
    public function toggleStatus($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);
        $mensagem = 'Status do enfermeiro atualizado.'; // Mensagem padrão

        if ($enfermeiro->usuario) {
            // Inverte o status
            $novoStatus = !$enfermeiro->usuario->statusAtivoUsuario;
            $enfermeiro->usuario->statusAtivoUsuario = $novoStatus;
            $enfermeiro->usuario->save();

            // Mensagem mais específica para o modal
            $acao = $novoStatus ? 'ativado' : 'desativado';
            $mensagem = "O enfermeiro(a) foi {$acao} com sucesso!";
        }

        // ADICIONANDO A FLAG 'status_changed' PARA DISPARAR O MODAL DE SUCESSO NO BLADE
        return redirect()->route('admin.manutencaoEnfermeiro')->with([
            'success' => $mensagem,
            'status_changed' => true // Flag para alteração de status
        ]);
    }

    public function confirmarExclusao($id)
    {
        $enfermeiro = Enfermeiro::findOrFail($id);
        return view('admin.desativarEnfermeiro', compact('enfermeiro'));
    }

    // MÉTODO EXCLUIR (EDITADO PARA MODAL DE SUCESSO)
    public function excluir($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);

        if ($enfermeiro->usuario) {
            $enfermeiro->usuario->delete();
        }

        $enfermeiro->delete();

        // ADICIONANDO A FLAG 'deleted' PARA DISPARAR O MODAL DE SUCESSO NO BLADE
        return redirect()->route('admin.manutencaoEnfermeiro')->with([
            'success' => 'Enfermeiro e usuário excluídos com sucesso.',
            'deleted' => true // NOVA FLAG para exclusão
        ]);
    }
}