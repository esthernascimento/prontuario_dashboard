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
        $unidadeLogada = Auth::guard('unidade')->user();

        if (!$unidadeLogada) {
            return redirect()->route('unidade.login')->with('error', 'Você precisa estar logado.');
        }

        // CORREÇÃO: Filtra apenas os recepcionistas da unidade logada
        $recepcionistas = $unidadeLogada->recepcionistas;

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

            $unidade = Auth::guard('unidade')->user();
            if (!$unidade) {
                return response()->json(['success' => false, 'message' => 'Unidade não autenticada.'], 401);
            }

            $data['senhaRecepcionista'] = Hash::make($data['senhaRecepcionista']);
            $data['statusAtivoRecepcionista'] = 1; // Agora este campo existe na tabela
            $data['idUnidadeFK'] = $unidade->idUnidadePK; // Pega o ID da unidade logada

            $recepcionista = Recepcionista::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Recepcionista cadastrado com sucesso!',
                'idRecepcionistaPK' => $recepcionista->idRecepcionistaPK
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Erro de validação', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao cadastrar: ' . $e->getMessage()], 500);
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

    public function quickView(Recepcionista $recepcionista)
    {
        $createdAt = $recepcionista->created_at->format('d/m/Y');

        return response()->json([
            'id' => $recepcionista->idRecepcionistaPK,
            'nome' => $recepcionista->nomeRecepcionista,
            'email' => $recepcionista->emailRecepcionista,
            'status' => $recepcionista->statusAtivoRecepcionista,
            'created_at' => $createdAt,
            'atendimentos' => $recepcionista->atendimentos ?? 0,
            'horas_trabalhadas' => $recepcionista->horas_trabalhadas ?? 0,
            'avaliacao' => $recepcionista->avaliacao ?? 'N/A'
        ]);
    }

    public function export()
    {
        // CORREÇÃO: Filtra apenas os recepcionistas da unidade logada
        $unidadeLogada = Auth::guard('unidade')->user();
        if (!$unidadeLogada) {
            return redirect()->route('unidade.login')->with('error', 'Você precisa estar logado.');
        }
        $recepcionistas = $unidadeLogada->recepcionistas;

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


    public function toggleStatus($id)
    {
        $recepcionista = Recepcionista::findOrFail($id);
        $mensagem = '';

        // Inverte o status atual
        $novoStatus = !$recepcionista->statusAtivoRecepcionista;
        $recepcionista->statusAtivoRecepcionista = $novoStatus;
        $recepcionista->save();

        $acao = $novoStatus ? 'ativado' : 'desativado';
        $mensagem = "O recepcionista foi {$acao} com sucesso!";

        return redirect()->route('unidade.manutencaoRecepcionista')->with('success', $mensagem);
    }
}
