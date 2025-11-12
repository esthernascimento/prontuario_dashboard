<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultaController extends Controller
{

    public function index(Request $request)
    {
        $query = Consulta::with(['medico', 'enfermeiro', 'unidade']);

        if ($request->has('paciente_id')) {
            $query->whereHas('prontuario', function ($q) use ($request) {
                $q->where('idPacienteFK', $request->paciente_id);
            });
        }

        return $query->orderBy('dataConsulta', 'desc')->paginate(15);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idProntuarioFK' => 'required|exists:tbProntuario,idProntuarioPK',
            'idMedicoFK' => 'required|exists:tbMedico,idMedicoPK',
            'idEnfermeiroFK' => 'nullable|exists:tbEnfermeiro,idEnfermeiroPK',
            'idUnidadeFK' => 'required|exists:tbUnidade,idUnidadePK',
            'dataConsulta' => 'required|date',
            'obsConsulta' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $consulta = Consulta::create($validator->validated());

        return response()->json($consulta, 201);
    }


    public function show($id)
    {

        $consulta = Consulta::with(['prontuario.paciente', 'medico', 'enfermeiro', 'unidade', 'medicamentos', 'exames'])->find($id);

        if (!$consulta) {
            return response()->json(['message' => 'Consulta não encontrada.'], 404);
        }

        return response()->json($consulta);
    }


    public function update(Request $request, $id)
    {
        $consulta = Consulta::find($id);
        if (!$consulta) {
            return response()->json(['message' => 'Consulta não encontrada.'], 404);
        }

        $data = $request->validate([
            'dataConsulta' => 'sometimes|date',
            'obsConsulta' => 'sometimes|nullable|string',

        ]);

        $consulta->update($data);

        return response()->json($consulta->fresh());
    }


    public function destroy($id)
    {
        $consulta = Consulta::find($id);
        if (!$consulta) {
            return response()->json(['message' => 'Consulta não encontrada.'], 404);
        }

        $consulta->delete();

        return response()->noContent();
    }

    public function showConsultaById($consulta_id)
    {
        $paciente = auth('sanctum')->user();

        if (!$paciente) {
            return response()->json(['message' => 'Paciente não autenticado.'], 401);
        }

        $consulta = Consulta::with(['medico', 'enfermeiro', 'unidade'])
            ->where('idConsultaPK', $consulta_id)
            ->where('idPacienteFK', $paciente->idPaciente)
            ->first();

        if (!$consulta) {
            return response()->json(['message' => 'Consulta não encontrada para este paciente.'], 404);
        }

        $dados = [
            'idConsulta' => $consulta->idConsultaPK,
            'dataConsulta' => $consulta->dataConsulta,
            'observacoes' => $consulta->observacoes,
            'examesSolicitados' => preg_split("/\r\n|\n|\r/", trim($consulta->examesSolicitados)),
            'medicamentosPrescritos' => !empty($consulta->medicamentosPrescritos)
                ? preg_split('/\r\n|\r|\n/', trim($consulta->medicamentosPrescritos))
                : [],
            'nomeMedico' => $consulta->nomeMedico,
            'crmMedico' => $consulta->crmMedico,
            'enfermeiro' => $consulta->enfermeiro,
            'unidade' => $consulta->unidade,
        ];


        return response()->json($dados);
    }
}
