<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    /**
     * Mostra a página de listagem de unidades, incluindo as "excluídas".
     */
    public function index()
    {
        $unidades = Unidade::withTrashed()->orderBy('nomeUnidade')->paginate(10);
        return view('admin.manutencaoUnidades', compact('unidades'));
    }

    /**
     * Mostra o formulário para criar uma nova unidade.
     */
    public function create()
    {
        return view('admin.cadastroUnidade');
    }

    /**
     * Salva a nova unidade no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação atualizada com os novos campos
        $request->validate([
            'nomeUnidade' => 'required|string|max:255|unique:tbUnidade,nomeUnidade',
            'tipoUnidade' => 'nullable|string|max:100',
            'telefoneUnidade' => 'nullable|string|max:20',
            'logradouroUnidade' => 'nullable|string|max:255',
            'numLogradouroUnidade' => 'nullable|string|max:20',
            'bairroUnidade' => 'nullable|string|max:100',
            'cepUnidade' => 'nullable|string|max:9',
            'cidadeUnidade' => 'nullable|string|max:100',
            'ufUnidade' => 'nullable|string|max:2',
            'estadoUnidade' => 'nullable|string|max:100',
            'paisUnidade' => 'nullable|string|max:100',
        ]);

        Unidade::create($request->all());

        return redirect()->route('admin.unidades.index')->with('success', 'Unidade cadastrada com sucesso!');
    }

    /**
     * Mostra o formulário para editar uma unidade.
     */
    public function edit(Unidade $unidade)
    {
        return view('admin.editarUnidade', compact('unidade'));
    }

    /**
     * Atualiza a unidade no banco de dados.
     */
    public function update(Request $request, Unidade $unidade)
    {
        // Validação atualizada com os novos campos
        $request->validate([
            'nomeUnidade' => "required|string|max:255|unique:tbUnidade,nomeUnidade,{$unidade->idUnidadePK},idUnidadePK",
            'tipoUnidade' => 'nullable|string|max:100',
            'telefoneUnidade' => 'nullable|string|max:20',
            'logradouroUnidade' => 'nullable|string|max:255',
            'numLogradouroUnidade' => 'nullable|string|max:20',
            'bairroUnidade' => 'nullable|string|max:100',
            'cepUnidade' => 'nullable|string|max:9',
            'cidadeUnidade' => 'nullable|string|max:100',
            'ufUnidade' => 'nullable|string|max:2',
            'estadoUnidade' => 'nullable|string|max:100',
            'paisUnidade' => 'nullable|string|max:100',
        ]);

        $unidade->update($request->all());

        return redirect()->route('admin.unidades.index')->with('success', 'Unidade atualizada com sucesso!');
    }

    // REMOVEMOS A FUNÇÃO 'destroy' PARA UNIFICAR A LÓGICA NO 'toggleStatus'.
    // A ação de "excluir" agora será uma desativação.

    /**
     * Ativa ou desativa (soft delete) uma unidade.
     */
    public function toggleStatus($id)
    {
        $unidade = Unidade::withTrashed()->findOrFail($id);
        
        if ($unidade->trashed()) {
            $unidade->restore();
            $mensagem = "A unidade foi ativada com sucesso!";
        } else {
            $unidade->delete();
            $mensagem = "A unidade foi desativada com sucesso!";
        }
        
        return redirect()->route('admin.unidades.index')->with('success', $mensagem);
    }
}