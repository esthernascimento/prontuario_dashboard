<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alergia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlergiaController extends Controller
{
    /**
     * Lista todas as alergias de um paciente específico.
     * GET /api/alergias?paciente_id=1
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:tbPaciente,idPacientePK',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $alergias = Alergia::where('idPacienteFK', $request->paciente_id)
            ->orderBy('descAlergia', 'asc')
            ->get();

        return response()->json($alergias);
    }

    /**
     * Adiciona uma nova alergia a um paciente.
     * POST /api/alergias
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idPacienteFK' => 'required|exists:tbPaciente,idPacientePK',
            'descAlergia' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $alergia = Alergia::create($validator->validated());

        return response()->json($alergia, 201);
    }

    /**
     * Exibe uma alergia específica.
     * GET /api/alergias/{id}
     */
    public function show($id)
    {
        $alergia = Alergia::find($id);

        if (!$alergia) {
            return response()->json(['message' => 'Alergia não encontrada.'], 404);
        }

        return response()->json($alergia);
    }

    /**
     * Atualiza a descrição de uma alergia.
     * PUT/PATCH /api/alergias/{id}
     */
    public function update(Request $request, $id)
    {
        $alergia = Alergia::find($id);
        if (!$alergia) {
            return response()->json(['message' => 'Alergia não encontrada.'], 404);
        }

        $data = $request->validate([
            'descAlergia' => 'required|string|max:255',
        ]);

        $alergia->update($data);

        return response()->json($alergia->fresh());
    }

    /**
     * Remove o registo de uma alergia.
     * DELETE /api/alergias/{id}
     */
    public function destroy($id)
    {
        $alergia = Alergia::find($id);
        if (!$alergia) {
            return response()->json(['message' => 'Alergia não encontrada.'], 404);
        }

        $alergia->delete();

        return response()->noContent();
    }
}
