<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExameController extends Controller
{
    /**
     * Lista todos os exames, com opção de filtrar por consulta ou paciente.
     * GET /api/exames?consulta_id=1
     * GET /api/exames?paciente_id=1
     */
    public function index(Request $request)
    {
        $query = Exame::query();

        if ($request->has('consulta_id')) {
            $query->where('idConsultaFK', $request->consulta_id);
        }

        if ($request->has('paciente_id')) {
            $query->where('idPacienteFK', $request->paciente_id);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Adiciona um novo pedido de exame a uma consulta.
     * POST /api/exames
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idConsultaFK' => 'required|exists:tbConsulta,idConsultaPK',
            'idPacienteFK' => 'required|exists:tbPaciente,idPacientePK',
            'descExame' => 'required|string|max:255',
            'resultadoExame' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exame = Exame::create($validator->validated());

        return response()->json($exame, 201);
    }

    /**
     * Exibe um exame específico.
     * GET /api/exames/{id}
     */
    public function show($id)
    {
        $exame = Exame::find($id);

        if (!$exame) {
            return response()->json(['message' => 'Exame não encontrado.'], 404);
        }

        return response()->json($exame);
    }

    
    public function update(Request $request, $id)
    {
        $exame = Exame::find($id);
        if (!$exame) {
            return response()->json(['message' => 'Exame não encontrado.'], 404);
        }

        $data = $request->validate([
            'descExame' => 'sometimes|string|max:255',
            'resultadoExame' => 'sometimes|nullable|string',
        ]);

        $exame->update($data);

        return response()->json($exame->fresh());
    }

    /**
     * Remove um pedido de exame.
     * DELETE /api/exames/{id}
     */
    public function destroy($id)
    {
        $exame = Exame::find($id);
        if (!$exame) {
            return response()->json(['message' => 'Exame não encontrado.'], 404);
        }

        $exame->delete();

        return response()->noContent();
    }
}
