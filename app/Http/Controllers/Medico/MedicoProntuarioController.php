<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Prontuario;
use App\Models\Consulta;
use App\Models\Medico;
use Illuminate\Support\Facades\Auth;
use App\Models\Unidade;
use App\Models\AnotacaoEnfermagem; // Importar AnotacaoEnfermagem

class MedicoProntuarioController extends Controller
{
       public function index()
    {
        $consultas_na_fila = Consulta::where('status_atendimento', 'AGUARDANDO_CONSULTA')
            ->with('paciente')
            ->orderByRaw("
                CASE
                    WHEN classificacao_risco = 'vermelho' THEN 1
                    WHEN classificacao_risco = 'laranja' THEN 2
                    WHEN classificacao_risco = 'amarelo' THEN 3
                    WHEN classificacao_risco = 'verde' THEN 4
                    WHEN classificacao_risco = 'azul' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('dataConsulta', 'asc')
            ->get();

        // Você pode remover $pacientes_historico se a view prontuarioMedico não usar mais
        $pacientes_historico = Paciente::orderBy('nomePaciente', 'asc')->get();

        return view('medico.prontuarioMedico', [
            'consultas_na_fila' => $consultas_na_fila,
            'pacientes' => $pacientes_historico
        ]);
    }

    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        $prontuario = Prontuario::where('idPacienteFK', $paciente->idPaciente)->first();

        if (!$prontuario) {
            return redirect()
                ->route('medico.prontuario')
                ->with('error', 'Este paciente ainda não possui prontuário.');
        }

         $consultas = Consulta::where('idProntuarioFK', $prontuario->idProntuarioPK)
            ->whereNotNull('idMedicoFK') // Apenas as finalizadas pelo médico
            ->orderBy('dataConsulta', 'desc')
            ->get();

        $anotacoesEnfermagem = AnotacaoEnfermagem::where('idPacienteFK', $paciente->idPaciente)
             ->orderBy('data_hora', 'desc')
             ->get();

        return view('medico.visualizarProntuario', compact('paciente', 'prontuario', 'consultas', 'anotacoesEnfermagem'));
    }

    public function create($id)
    {
        $paciente = Paciente::findOrFail($id);

        if (!$paciente->statusPaciente) {
        }

        $medico = Auth::user()->medico;
         if (!$medico) {
            
         }

        Prontuario::firstOrCreate(
            ['idPacienteFK' => $paciente->idPaciente],
            ['dataAbertura' => now()->toDateString()]
        );

        return view('medico.cadastrarProntuario', [
            'paciente' => $paciente,
            'medico' => $medico,
            'consulta' => null, 
            'anotacoesEnfermagem' => null 
        ]);
    }

  
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'dataConsulta' => 'required|date',
            'observacoes' => 'nullable|string|max:2000',
            'examesSolicitados' => 'nullable|string|max:2000',
            'medicamentosPrescritos' => 'nullable|string|max:2000',
        ]);

        $paciente = Paciente::findOrFail($id);

        if (!$paciente->statusPaciente) {
            // ... (tratamento de erro)
        }

        $medico = Auth::user()->medico;
         if (!$medico) {
            // ... (tratamento de erro)
         }


        $prontuario = Prontuario::firstOrCreate(
             ['idPacienteFK' => $paciente->idPaciente],
             ['dataAbertura' => now()->toDateString()]
         );

        $consulta = new Consulta();
        $consulta->idProntuarioFK = $prontuario->idProntuarioPK;
        $consulta->idMedicoFK = $medico->idMedicoPK;
        $consulta->nomeMedico = $medico->nomeMedico;
        $consulta->crmMedico = $medico->crmMedico;
        $consulta->dataConsulta = $validated['dataConsulta'];
        $consulta->observacoes = $validated['observacoes'] ?? null;
        $consulta->examesSolicitados = $validated['examesSolicitados'] ?? null;
        $consulta->medicamentosPrescritos = $validated['medicamentosPrescritos'] ?? null;
        $consulta->status_atendimento = 'FINALIZADO';
        $consulta->idPacienteFK = $paciente->idPaciente;

        $consulta->save();

        return redirect()
            ->route('medico.visualizarProntuario', $id) // Leva para o histórico após salvar
            ->with('success', 'Consulta registrada com sucesso!');
    }

    public function edit($idConsulta)
    {
        $consulta = Consulta::with(['paciente', 'prontuario'])->findOrFail($idConsulta);
        $paciente = $consulta->paciente;
        $prontuario = $consulta->prontuario;
        $medico = Auth::user()->medico;
         if (!$medico) {
             // ... (tratamento de erro)
         }


        $anotacoesEnfermagem = AnotacaoEnfermagem::where('idPacienteFK', $paciente->idPaciente)
                                    ->orderBy('data_hora', 'desc')
                                    ->get();

         if (!$prontuario && $paciente) {
            $prontuario = Prontuario::firstOrCreate(
                ['idPacienteFK' => $paciente->idPaciente],
                ['dataAbertura' => now()->toDateString()]
            );
            $consulta->idProntuarioFK = $prontuario->idProntuarioPK;
            $consulta->save();
         }

        // Retorna a MESMA view, passando a $consulta existente
        return view('medico.cadastrarProntuario', compact('consulta', 'paciente', 'medico', 'anotacoesEnfermagem'));
    }

    
    public function update(Request $request, $idConsulta)
    {
        $consulta = Consulta::findOrFail($idConsulta);
        $medico = Auth::user()->medico;
         if (!$medico) {
            // ... (tratamento de erro)
         }


        $validated = $request->validate([
            'dataConsulta' => 'required|date',
            'observacoes' => 'nullable|string|max:2000',
            'examesSolicitados' => 'nullable|string|max:2000',
            'medicamentosPrescritos' => 'nullable|string|max:2000',
        ]);

        $consulta->idMedicoFK = $medico->idMedicoPK;
        $consulta->nomeMedico = $medico->nomeMedico;
        $consulta->crmMedico = $medico->crmMedico;
        $consulta->status_atendimento = 'FINALIZADO';

        $consulta->dataConsulta = $validated['dataConsulta'];
        $consulta->observacoes = $validated['observacoes'] ?? null;
        $consulta->examesSolicitados = $validated['examesSolicitados'] ?? null;
        $consulta->medicamentosPrescritos = $validated['medicamentosPrescritos'] ?? null;

        if (!$consulta->idProntuarioFK && $consulta->paciente) {
             $prontuario = Prontuario::firstOrCreate(
                ['idPacienteFK' => $consulta->paciente->idPaciente],
                ['dataAbertura' => now()->toDateString()]
             );
             $consulta->idProntuarioFK = $prontuario->idProntuarioPK;
        }

        $consulta->save();

        return redirect()
            ->route('medico.prontuario')
            ->with('success', 'Atendimento finalizado com sucesso!');
    }

    public function destroy($idConsulta)
    {
        // ... (seu código destroy continua igual) ...
    }
}

