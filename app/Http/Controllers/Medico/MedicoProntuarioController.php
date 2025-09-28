<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;

class MedicoProntuarioController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::orderBy('nome')->get();
        return view('medico.prontuarioMedico', compact('pacientes'));
    }

    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('medico.prontuario_detalhe', compact('paciente'));
    }
}
