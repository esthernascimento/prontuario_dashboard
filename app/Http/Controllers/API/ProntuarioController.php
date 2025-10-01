<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prontuario;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProntuarioController extends Controller
{
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'idPacienteFK' => 'required|exists:tbPaciente,idPacientePK|unique:tbProntuario,idPacienteFK',
        ]);

        $data['dataAbertura'] = Carbon::now();

        $prontuario = Prontuario::create($data);

        return response()->json($prontuario, 201);
    }

  
    public function show($idPaciente)
    {
     
        $paciente = Paciente::find($idPaciente);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado.'], 404);
        }

       
        $prontuario = Prontuario::where('idPacienteFK', $idPaciente)->first();
        if (!$prontuario) {
            return response()->json(['message' => 'Prontuário para este paciente ainda não foi aberto.'], 404);
        }

        
        $prontuarioCompleto = $prontuario->load([
           
            'paciente', 
            
            
            'paciente.alergias',

            
            'consultas' => function ($query) {
            
                $query->orderBy('dataConsulta', 'desc');
            },
            
            
            'consultas.medico:idMedicoPK,nomeMedico', 
            'consultas.enfermeiro:idEnfermeiroPK,nomeEnfermeiro',
            'consultas.unidade:idUnidadePK,nomeUnidade', 
            'consultas.medicamentos',
            'consultas.exames',       
        ]);

        return response()->json($prontuarioCompleto);
    }
}
