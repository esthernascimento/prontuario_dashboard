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
            $data['statusAtivoRecepcionista'] = 1; // Define como ativo por padrão
            
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
    
    /**
     * Visualização rápida de um recepcionista (para AJAX)
     */
    public function quickView(Recepcionista $recepcionista)
    {
        // Formata a data para exibição
        $createdAt = $recepcionista->created_at->format('d/m/Y');
        
        return response()->json([
            'id' => $recepcionista->idRecepcionistaPK,
            'nome' => $recepcionista->nomeRecepcionista,
            'email' => $recepcionista->emailRecepcionista,
            'status' => $recepcionista->statusAtivoRecepcionista,
            'created_at' => $createdAt,
            // Estes campos podem ser adicionados se existirem na tabela
            'atendimentos' => $recepcionista->atendimentos ?? 0,
            'horas_trabalhadas' => $recepcionista->horas_trabalhadas ?? 0,
            'avaliacao' => $recepcionista->avaliacao ?? 'N/A'
        ]);
    }
    
    /**
     * Exporta recepcionistas para Excel
     */
    public function export()
    {
        // Implementação básica - você pode usar uma biblioteca como Laravel Excel
        $recepcionistas = Recepcionista::all();
        
        $csv = "Nome,Email,Status,Cadastrado em\n";
        
        foreach ($recepcionistas as $recepcionista) {
            $status = $recepcionista->statusAtivoRecepcionista == 1 ? 'Ativo' : 'Inativo';
            $dataCadastro = $recepcionista->created_at->format('d/m/Y');
            $csv .= "{$recepcionista->nomeRecepcionista},{$recepcionista->emailRecepcionista},{$status},{$dataCadastro}\n";
        }
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="recepcionistas.csv"',
        ];
        
        return response($csv, 200, $headers);
    }
}