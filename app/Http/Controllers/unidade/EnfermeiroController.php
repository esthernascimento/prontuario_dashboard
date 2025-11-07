<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Enfermeiro;
use App\Models\Usuario;
use App\Models\Unidade;
use App\Mail\emailEnfermeiro;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EnfermeiroController extends Controller
{
    // Listagem de enfermeiros
    public function index()
    {
        $enfermeiros = Enfermeiro::with('usuario')->get();
        return view('unidade.manutencaoEnfermeiro', compact('enfermeiros'));
    }

    /**
     * Mostra o formulário de cadastro e envia a lista de unidades.
     */
    public function create()
    {
        $unidades = Unidade::orderBy('nomeUnidade')->get();
        return view('unidade.cadastroEnfermeiro', compact('unidades'));
    }

    /**
     * Mostra o formulário de edição de enfermeiro.
     */
    public function edit($id)
    {
        $enfermeiro = Enfermeiro::with(['usuario', 'unidades'])->findOrFail($id);
        $unidades = Unidade::orderBy('nomeUnidade')->get();
        
        return view('unidade.editarEnfermeiro', compact('enfermeiro', 'unidades'));
    }

    /**
     * Salva o novo enfermeiro e as suas unidades de trabalho.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nomeEnfermeiro' => 'required|string|max:255',
                'corenEnfermeiro' => 'required|string|max:20|unique:tbEnfermeiro,corenEnfermeiro',
                'emailEnfermeiro' => 'required|email|max:255|unique:tbUsuario,emailUsuario',
                'especialidadeEnfermeiro' => 'nullable|string|max:100',
                'genero' => 'required|string|in:Masculino,Feminino,Outro',
                'unidades' => 'nullable|array',
                'unidades.*' => 'exists:tbUnidade,idUnidadePK',
            ], [
                'nomeEnfermeiro.required' => 'O nome do enfermeiro é obrigatório.',
                'corenEnfermeiro.required' => 'O COREN é obrigatório.',
                'emailEnfermeiro.required' => 'O e-mail é obrigatório.',
                'emailEnfermeiro.unique' => 'Este e-mail já está cadastrado.',
                'genero.required' => 'O gênero é obrigatório.',
            ]);

            $senhaTemporaria = Str::random(10);

            // 1. Primeiro cria o usuário
            $usuario = new Usuario();
            $usuario->nomeUsuario = $request->nomeEnfermeiro;
            $usuario->emailUsuario = $request->emailEnfermeiro;
            $usuario->senhaUsuario = Hash::make($senhaTemporaria);
            $usuario->statusAtivoUsuario = 1;
            $usuario->statusSenhaUsuario = true;
            $usuario->save();

            // 2. Depois cria o enfermeiro vinculado ao usuário - ✅ CORREÇÃO: usar 'id_usuario'
            $enfermeiro = new Enfermeiro();
            $enfermeiro->id_usuario = $usuario->idUsuarioPK; // ✅ MUDOU PARA 'id_usuario'
            $enfermeiro->nomeEnfermeiro = $request->nomeEnfermeiro;
            $enfermeiro->corenEnfermeiro = $request->corenEnfermeiro;
            $enfermeiro->emailEnfermeiro = $request->emailEnfermeiro;
            $enfermeiro->especialidadeEnfermeiro = $request->input('especialidadeEnfermeiro', '');
            $enfermeiro->genero = $request->genero;
            $enfermeiro->save();

            // 3. Associa as unidades se existirem
            if ($request->has('unidades')) {
                $enfermeiro->unidades()->sync($request->unidades);
            }

            // 4. Envia e-mail com as credenciais
            Mail::to($usuario->emailUsuario)->send(new EmailEnfermeiro($usuario, $senhaTemporaria));

            return response()->json([
                'success' => true,
                'message' => 'Enfermeiro pré-cadastrado com sucesso!'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao cadastrar enfermeiro: ' . $e->getMessage()
            ], 500);
        }
    }

    // 🔥 CORREÇÃO: Rota de redirecionamento é da 'unidade'
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
            'unidades' => 'nullable|array',
            'unidades.*' => 'exists:tbUnidade,idUnidadePK',
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

        // Atualiza as unidades do enfermeiro
        if ($request->has('unidades')) {
            $enfermeiro->unidades()->sync($request->unidades);
        } else {
            $enfermeiro->unidades()->detach();
        }

        return redirect()->route('unidade.manutencaoEnfermeiro')->with([
            'success' => 'Dados atualizados com sucesso.',
            'updated' => true
        ]);
    }

    // 🔥 CORREÇÃO: View de visualização agora pertence à 'unidade'
    public function show($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);
        return view('unidade.visualizarEnfermeiro', compact('enfermeiro'));
    }

    // 🔥 CORREÇÃO: Rota de redirecionamento é da 'unidade'
    public function toggleStatus($id)
    {
        $enfermeiro = Enfermeiro::with('usuario')->findOrFail($id);
        $mensagem = 'Status do enfermeiro atualizado.';

        if ($enfermeiro->usuario) {
            $novoStatus = !$enfermeiro->usuario->statusAtivoUsuario;
            $enfermeiro->usuario->statusAtivoUsuario = $novoStatus;
            $enfermeiro->usuario->save();

            $acao = $novoStatus ? 'ativado' : 'desativado';
            $mensagem = "O enfermeiro(a) foi {$acao} com sucesso!";
        }

        return redirect()->route('unidade.manutencaoEnfermeiro')->with([
            'success' => $mensagem,
            'status_changed' => true
        ]);
    }

    public function syncUnidades(Request $request, Enfermeiro $enfermeiro)
    {
        $request->validate([
            'unidades' => 'required|array',
            'unidades.*' => 'exists:tbUnidade,idUnidadePK',
        ]);

        $enfermeiro->unidades()->sync($request->unidades);

        return response()->json([
            'message' => 'Unidades do enfermeiro atualizadas com sucesso!',
            'enfermeiro' => $enfermeiro->load('unidades')
        ]);
    }
}