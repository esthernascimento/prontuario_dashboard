<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnidadeController extends Controller
{
    /**
     * Lista todas as unidades de saúde.
     * GET /api/unidades
     */
    public function index()
    {
        return Unidade::orderBy('nomeUnidade', 'asc')->paginate(20);
    }

    /**
     * Cria uma nova unidade de saúde.
     * POST /api/unidades
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomeUnidade' => 'required|string|max:255|unique:tbUnidade',
            'tipoUnidade' => 'nullable|string|max:100',
            'enderecoUnidade' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $unidade = Unidade::create($validator->validated());

        return response()->json($unidade, 201);
    }

    /**
     * Exibe uma unidade de saúde específica.
     * GET /api/unidades/{id}
     */
    public function show($id)
    {
        $unidade = Unidade::find($id);

        if (!$unidade) {
            return response()->json(['message' => 'Unidade não encontrada.'], 404);
        }

        return response()->json($unidade);
    }

    /**
     * Atualiza uma unidade de saúde.
     * PUT/PATCH /api/unidades/{id}
     */
    public function update(Request $request, $id)
    {
        $unidade = Unidade::find($id);
        if (!$unidade) {
            return response()->json(['message' => 'Unidade não encontrada.'], 404);
        }

        $data = $request->validate([
            'nomeUnidade' => "sometimes|string|max:255|unique:tbUnidade,nomeUnidade,{$id},idUnidadePK",
            'tipoUnidade' => 'sometimes|nullable|string|max:100',
            'enderecoUnidade' => 'sometimes|nullable|string|max:255',
        ]);

        $unidade->update($data);

        return response()->json($unidade->fresh());
    }

    /**
     * Remove uma unidade de saúde.
     * DELETE /api/unidades/{id}
     */
    public function destroy($id)
    {
        $unidade = Unidade::find($id);
        if (!$unidade) {
            return response()->json(['message' => 'Unidade não encontrada.'], 404);
        }

        $unidade->delete();

        return response()->noContent();
    }
}
