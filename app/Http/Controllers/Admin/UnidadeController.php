<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    
    public function index()
    {
        $unidades = Unidade::withTrashed()->orderBy('nomeUnidade')->paginate(10);
        return view('admin.manutencaoUnidades', compact('unidades'));
    }

    public function create()
    {
        return view('admin.cadastroUnidade');
    }

    public function store(Request $request)
    {
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

    
     
    public function edit(Unidade $unidade)
    {
        return view('admin.editarUnidade', compact('unidade'));
    }

    public function update(Request $request, Unidade $unidade)
    {
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