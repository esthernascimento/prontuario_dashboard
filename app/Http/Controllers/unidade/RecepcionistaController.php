<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use App\Models\Recepcionista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Para obter o Admin logado

class RecepcionistaController extends Controller
{
    /**
     * Lista todos os recepcionistas.
     */
    public function index()
    {
        $recepcionistas = Recepcionista::paginate(15);
        // Retornará uma view de manutenção que criaremos depois
        // return view('admin.manutencaoRecepcionistas', compact('recepcionistas')); 
        return response()->json($recepcionistas); // Temporário para teste
    }

    /**
     * Cria um novo recepcionista.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomeRecepcionista' => 'required|string|max:255',
            'emailRecepcionista' => ['required', 'email', 'unique:tbRecepcionista,emailRecepcionista'],
            'senhaRecepcionista' => 'required|string|min:6',
        ]);

        $data['senhaRecepcionista'] = Hash::make($data['senhaRecepcionista']);
        // Associa o recepcionista ao Admin que está a fazer o cadastro
        $data['idAdminFK'] = Auth::guard('admin')->id(); 

        $recepcionista = Recepcionista::create($data);

        // Retornará para a página de listagem com mensagem de sucesso
        // return redirect()->route('admin.recepcionistas.index')->with('success', 'Recepcionista criado com sucesso!');
        return response()->json($recepcionista, 201); // Temporário para teste
    }

    /**
     * Exibe um recepcionista específico.
     */
    public function show(Recepcionista $recepcionista)
    {
        // Retornará uma view de detalhes que criaremos depois
        // return view('admin.detalhesRecepcionista', compact('recepcionista'));
         return response()->json($recepcionista); // Temporário para teste
    }

    /**
     * Atualiza um recepcionista.
     */
    public function update(Request $request, Recepcionista $recepcionista)
    {
        $data = $request->validate([
            'nomeRecepcionista' => 'sometimes|string|max:255',
            'emailRecepcionista' => ['sometimes', 'email', Rule::unique('tbRecepcionista')->ignore($recepcionista->idRecepcionistaPK, 'idRecepcionistaPK')],
            'senhaRecepcionista' => 'sometimes|nullable|string|min:6', // Senha opcional na atualização
        ]);

        if (!empty($data['senhaRecepcionista'])) {
            $data['senhaRecepcionista'] = Hash::make($data['senhaRecepcionista']);
        } else {
            unset($data['senhaRecepcionista']); // Remove a senha do array se estiver vazia
        }

        $recepcionista->update($data);

        // Retornará para a página de listagem com mensagem de sucesso
        // return redirect()->route('admin.recepcionistas.index')->with('success', 'Recepcionista atualizado com sucesso!');
        return response()->json($recepcionista->fresh()); // Temporário para teste
    }

    /**
     * Remove (soft delete) um recepcionista.
     */
    public function destroy(Recepcionista $recepcionista)
    {
        $recepcionista->delete();
        // Retornará para a página de listagem com mensagem de sucesso
        // return redirect()->route('admin.recepcionistas.index')->with('success', 'Recepcionista desativado com sucesso!');
        return response()->noContent(); // Temporário para teste
    }
}
