<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use App\Models\Recepcionista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class RecepcionistaController extends Controller
{
    public function index()
    {
        // 🔥 REMOVIDO: paginate() e substituído por all()
        $recepcionistas = Recepcionista::all();
        return view('unidade.manutencaoRecepcionista', compact('recepcionistas'));
    }

    public function create()
    {
        return view('unidade.cadastroRecepcionista');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nomeRecepcionista' => 'required|string|max:255',
                'emailRecepcionista' => ['required', 'email', 'unique:tbRecepcionista,emailRecepcionista'],
                'senhaRecepcionista' => 'required|string|min:6',
            ]);

            $data['senhaRecepcionista'] = Hash::make($data['senhaRecepcionista']);
            
            $unidade = Auth::guard('unidade')->user();
            
            if (!$unidade) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unidade não autenticada.'
                    ], 401);
                }
                return back()->withErrors(['auth' => 'Unidade não autenticada.']);
            }

            $data['idUnidadeFK'] = $unidade->idUnidadePK;

            $recepcionista = Recepcionista::create($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Recepcionista cadastrado com sucesso!',
                    'idRecepcionistaPK' => $recepcionista->idRecepcionistaPK
                ], 201);
            }

            return redirect()->route('unidade.manutencaoRecepcionista')->with('success', 'Recepcionista cadastrado com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao cadastrar recepcionista: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erro ao cadastrar recepcionista: ' . $e->getMessage()]);
        }
    }

    public function show(Recepcionista $recepcionista)
    {
        return view('unidade.visualizarRecepcionista', compact('recepcionista'));
    }

    public function edit(Recepcionista $recepcionista)
    {
        return view('unidade.editarRecepcionista', compact('recepcionista'));
    }

    public function update(Request $request, Recepcionista $recepcionista)
    {
        $data = $request->validate([
            'nomeRecepcionista' => 'sometimes|string|max:255',
            'emailRecepcionista' => ['sometimes', 'email', Rule::unique('tbRecepcionista')->ignore($recepcionista->idRecepcionistaPK, 'idRecepcionistaPK')],
            'senhaRecepcionista' => 'sometimes|nullable|string|min:6',
        ]);

        if (!empty($data['senhaRecepcionista'])) {
            $data['senhaRecepcionista'] = Hash::make($data['senhaRecepcionista']);
        } else {
            unset($data['senhaRecepcionista']);
        }

        $recepcionista->update($data);

        return redirect()->route('unidade.manutencaoRecepcionista')->with('success', 'Recepcionista atualizado com sucesso!');
    }

    public function destroy(Recepcionista $recepcionista)
    {
        $recepcionista->delete();
        return redirect()->route('unidade.manutencaoRecepcionista')->with('success', 'Recepcionista excluído com sucesso!');
    }
}