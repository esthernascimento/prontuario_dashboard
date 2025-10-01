<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicamentoController extends Controller
{
    /**
     * Lista todos os medicamentos, com opção de filtrar por consulta ou paciente.
     * GET /api/medicamentos?consulta_id=1
     * GET /api/medicamentos?paciente_id=1
     */
    public function index(Request $request)
    {
        $query = Medicamento::query();

        if ($request->has('consulta_id')) {
            $query->where('idConsultaFK', $request->consulta_id);
        }

        if ($request->has('paciente_id')) {
            $query->where('idPacienteFK', $request->paciente_id);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Adiciona um novo medicamento a uma consulta.
     * POST /api/medicamentos
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idConsultaFK' => 'required|exists:tbConsulta,idConsultaPK',
            'idPacienteFK' => 'required|exists:tbPaciente,idPacientePK',
            'descMedicamento' => 'required|string|max:255',
            'posologia' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $medicamento = Medicamento::create($validator->validated());

        return response()->json($medicamento, 201);
    }

    /**
     * Exibe um medicamento específico.
     * GET /api/medicamentos/{id}
     */
    public function show($id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json(['message' => 'Medicamento não encontrado.'], 404);
        }

        return response()->json($medicamento);
    }

    /**
     * Atualiza um medicamento.
     * PUT/PATCH /api/medicamentos/{id}
     */
    public function update(Request $request, $id)
    {
        $medicamento = Medicamento::find($id);
        if (!$medicamento) {
            return response()->json(['message' => 'Medicamento não encontrado.'], 404);
        }

        $data = $request->validate([
            'descMedicamento' => 'sometimes|string|max:255',
            'posologia' => 'sometimes|nullable|string',
        ]);

        $medicamento->update($data);

        return response()->json($medicamento->fresh());
    }

    /**
     * Remove um medicamento.
     * DELETE /api/medicamentos/{id}
     */
    public function destroy($id)
    {
        $medicamento = Medicamento::find($id);
        if (!$medicamento) {
            return response()->json(['message' => 'Medicamento não encontrado.'], 404);
        }

        $medicamento->delete();

        return response()->noContent();
    }
}
