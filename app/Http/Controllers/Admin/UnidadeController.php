<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnidadeController extends Controller
{
    /**
     * Lista todas as unidades de saúde.
     */
    public function index()
    {
        return Unidade::orderBy('nomeUnidade')->paginate(15);
    }

    /**
     * Cria uma nova unidade de saúde.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomeUnidade' => 'required|string|max:255|unique:tbUnidade,nomeUnidade',
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
     */
    public function show(Unidade $unidade)
    {
        return $unidade;
    }

    /**
     * Atualiza uma unidade de saúde.
     */
    public function update(Request $request, Unidade $unidade)
    {
        $data = $request->validate([
            'nomeUnidade' => "sometimes|string|max:255|unique:tbUnidade,nomeUnidade,{$unidade->idUnidadePK},idUnidadePK",
            'tipoUnidade' => 'sometimes|nullable|string|max:100',
            'enderecoUnidade' => 'sometimes|nullable|string|max:255',
        ]);

        $unidade->update($data);

        return response()->json($unidade->fresh());
    }

    /**
     * Remove (soft delete) uma unidade de saúde.
     */
    public function destroy(Unidade $unidade)
    {
        $unidade->delete();
        return response()->noContent();
    }
}
