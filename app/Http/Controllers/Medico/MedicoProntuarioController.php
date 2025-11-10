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
use App\Models\Medicamento;
use App\Models\Exame;
use App\Models\AnotacaoEnfermagem;
use Illuminate\Support\Facades\DB; // Importar DB

class MedicoProntuarioController extends Controller
{
    /**
     * Exibe a FILA DE ATENDIMENTO do médico (tela principal)
     */
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

        $pacientes_historico = Paciente::orderBy('nomePaciente', 'asc')->get();

        return view('medico.prontuarioMedico', [
            'consultas_na_fila' => $consultas_na_fila,
            'pacientes' => $pacientes_historico
        ]);
    }

    /**
     * Exibe o prontuário completo de um paciente (histórico)
     */
    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        $prontuario = Prontuario::where('idPacienteFK', $paciente->idPaciente)->first();

        if (!$prontuario) {
            return redirect()
                ->route('medico.prontuario') // CORRIGIDO
                ->with('error', 'Este paciente ainda não possui prontuário.');
        }

         $consultas = Consulta::where('idProntuarioFK', $prontuario->idProntuarioPK)
            ->whereNotNull('idMedicoFK') 
            ->orderBy('dataConsulta', 'desc')
            ->get();

        $anotacoesEnfermagem = AnotacaoEnfermagem::where('idPacienteFK', $paciente->idPaciente)
             ->orderBy('data_hora', 'desc')
             ->get();

        return view('medico.visualizarProntuario', compact('paciente', 'prontuario', 'consultas', 'anotacoesEnfermagem'));
    }

    /**
     * Exibe o formulário para CRIAR nova consulta (Médico inicia do zero)
     */
    public function create($id)
    {
        $paciente = Paciente::findOrFail($id);

        if (!$paciente->statusPaciente) {
            return redirect()->route('medico.prontuario')->with('error', 'Paciente inativo.'); // CORRIGIDO
        }

        $medico = Auth::user()->medico;
        if (!$medico) {
            return redirect()->route('medico.prontuario')->with('error', 'Médico não encontrado.'); // CORRIGIDO
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

    /**
     * Armazena uma NOVA consulta/prontuário (Médico inicia do zero)
     */
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'dataConsulta' => 'required|date',
            'observacoes' => 'nullable|string|max:2000',
            'exames_solicitados' => 'nullable|array', 
            'exames_solicitados.*' => 'string|max:255',
            'medicamentos_prescritos' => 'nullable|array', 
            'medicamentos_prescritos.*' => 'string|max:255',
        ]);

        $paciente = Paciente::findOrFail($id);
        $medico = Auth::user()->medico;

        if (!$medico) {
            return redirect()->route('medico.prontuario')->with('error', 'Médico não encontrado.'); // CORRIGIDO
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

        $examesCheckboxes = $validated['exames_solicitados'] ?? [];
        $consulta->examesSolicitados = implode("\n", $examesCheckboxes); 

        $medicamentosCheckboxes = $validated['medicamentos_prescritos'] ?? [];
        $consulta->medicamentosPrescritos = implode("\n", $medicamentosCheckboxes); 
        
        $consulta->status_atendimento = 'FINALIZADO';
        $consulta->idPacienteFK = $paciente->idPaciente;

        DB::transaction(function () use ($consulta, $paciente, $prontuario, $medicamentosCheckboxes, $examesCheckboxes) {
            $consulta->save(); 

            if (!empty($medicamentosCheckboxes)) {
                foreach ($medicamentosCheckboxes as $medicamento) {
                    Medicamento::create([
                        'idConsultaFK' => $consulta->idConsultaPK,
                        'idProntuarioFK' => $prontuario->idProntuarioPK,
                        'idPacienteFK' => $paciente->idPaciente,
                        'descMedicamento' => $medicamento,
                        'nomeMedicamento' => $medicamento,
                    ]);
                }
            }

            // --- Bloco CORRIGIDO (store) ---
            if (!empty($examesCheckboxes)) {
                foreach ($examesCheckboxes as $exame) {
                    $nomeExame = $exame;
                    $descExame = $exame;
                     if (strpos($exame, ':') !== false) {
                         $partes = explode(':', $exame, 2); $nomeExame = trim($partes[0]); $descExame = trim($partes[1]) ?: $nomeExame;
                    } elseif (strpos($exame, ' - ') !== false) {
                         $partes = explode(' - ', $exame, 2); $nomeExame = trim($partes[0]); $descExame = trim($partes[1]) ?: $nomeExame;
                    } elseif (strlen($exame) > 50) {
                         $palavras = explode(' ', $exame); $nomeExame = implode(' ', array_slice($palavras, 0, 3));
                    }
                    
                    Exame::create([
                        'idConsultaFK' => $consulta->idConsultaPK,
                        'descExame' => $descExame,
                        'nomeExame' => $nomeExame,
                        'dataExame' => $consulta->dataConsulta ?? now(),
                        'statusExame' => 'SOLICITADO',
                        'idPacienteFK' => $paciente->idPaciente, // <-- CORREÇÃO
                        'idProntuarioFK' => $prontuario->idProntuarioPK, // <-- CORREÇÃO
                    ]);
                }
            }
        });

        return redirect()
            ->route('medico.visualizarProntuario', $id) 
            ->with('success', 'Consulta registrada com sucesso!');
    }

    /**
     * Exibe o formulário para ATENDER/EDITAR uma consulta vinda da fila
     */
    public function edit($idConsulta)
    {
        $consulta = Consulta::with(['paciente', 'prontuario'])->findOrFail($idConsulta);
        $paciente = $consulta->paciente;
        $prontuario = $consulta->prontuario;
        $medico = Auth::user()->medico;

        if (!$medico) {
            return redirect()->route('medico.prontuario')->with('error', 'Médico não encontrado.');
        }

        $anotacoesEnfermagem = AnotacaoEnfermagem::where('idPacienteFK', $paciente->idPaciente)
            ->where('created_at', '>=', $consulta->created_at->subDay()) 
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

        return view('medico.cadastrarProntuario', compact('consulta', 'paciente', 'medico', 'anotacoesEnfermagem'));
    }

    /**
     * Atualiza e FINALIZA uma consulta vinda da fila
     */
    public function update(Request $request, $idConsulta)
    {
        $consulta = Consulta::findOrFail($idConsulta);
        $medico = Auth::user()->medico;

        if (!$medico) {
            return redirect()->route('medico.prontuario')->with('error', 'Médico não encontrado.');
        }

        $validated = $request->validate([
            'dataConsulta' => 'required|date',
            'observacoes' => 'nullable|string|max:2000',
            'exames_solicitados' => 'nullable|array',
            'exames_solicitados.*' => 'string|max:255',
            'medicamentos_prescritos' => 'nullable|array',
            'medicamentos_prescritos.*' => 'string|max:255',
        ]);

        $consulta->idMedicoFK = $medico->idMedicoPK;
        $consulta->nomeMedico = $medico->nomeMedico;
        $consulta->crmMedico = $medico->crmMedico;
        $consulta->status_atendimento = 'FINALIZADO';
        $consulta->dataConsulta = $validated['dataConsulta'];
        $consulta->observacoes = $validated['observacoes'] ?? null;

        $examesCheckboxes = $validated['exames_solicitados'] ?? [];
        $consulta->examesSolicitados = implode("\n", $examesCheckboxes);

        $medicamentosCheckboxes = $validated['medicamentos_prescritos'] ?? [];
        $consulta->medicamentosPrescritos = implode("\n", $medicamentosCheckboxes);

        if (!$consulta->idProntuarioFK && $consulta->paciente) {
            $prontuario = Prontuario::firstOrCreate(
                ['idPacienteFK' => $consulta->paciente->idPaciente],
                ['dataAbertura' => now()->toDateString()]
            );
            $consulta->idProntuarioFK = $prontuario->idProntuarioPK;
        }

        DB::transaction(function () use ($consulta, $medicamentosCheckboxes, $examesCheckboxes) {
            $consulta->save();

            Medicamento::where('idConsultaFK', $consulta->idConsultaPK)->forceDelete();
            Exame::where('idConsultaFK', $consulta->idConsultaPK)->forceDelete();

            if (!empty($medicamentosCheckboxes)) {
                foreach ($medicamentosCheckboxes as $medicamento) {
                    Medicamento::create([
                        'idConsultaFK' => $consulta->idConsultaPK,
                        'idPacienteFK' => $consulta->idPacienteFK,
                        'idProntuarioFK' => $consulta->idProntuarioFK,
                        'descMedicamento' => $medicamento,
                        'nomeMedicamento' => $medicamento,
                    ]);
                }
            }

            // --- Bloco CORRIGIDO (update) ---
            if (!empty($examesCheckboxes)) {
                foreach ($examesCheckboxes as $exame) {
                    $nomeExame = $exame;
                    $descExame = $exame;
                     if (strpos($exame, ':') !== false) {
                         $partes = explode(':', $exame, 2); $nomeExame = trim($partes[0]); $descExame = trim($partes[1]) ?: $nomeExame;
                    } elseif (strpos($exame, ' - ') !== false) {
                         $partes = explode(' - ', $exame, 2); $nomeExame = trim($partes[0]); $descExame = trim($partes[1]) ?: $nomeExame;
                    } elseif (strlen($exame) > 50) {
                         $palavras = explode(' ', $exame); $nomeExame = implode(' ', array_slice($palavras, 0, 3));
                    }
                    
                    Exame::create([
                        'idConsultaFK' => $consulta->idConsultaPK,
                        'descExame' => $descExame,
                        'nomeExame' => $nomeExame,
                        'dataExame' => $consulta->dataConsulta ?? now(),
                        'statusExame' => 'SOLICITADO',
                        
                        // --- CORREÇÃO ADICIONADA (aqui estava o erro) ---
                        'idPacienteFK' => $consulta->idPacienteFK,
                        'idProntuarioFK' => $consulta->idProntuarioFK,
                        // --- FIM DA CORREÇÃO ---
                    ]);
                }
            }
        });

        return redirect()
            ->route('medico.prontuario') // Redireciona de volta para a fila
            ->with('success', 'Atendimento finalizado com sucesso!');
    }
    
    /**
     * Gera o PDF de Pedido de Exames.
     */
    public function gerarPdfExames($idConsulta)
    {
        $consulta = Consulta::findOrFail($idConsulta);
        $paciente = $consulta->paciente;
        
        return response("PDF de Exames para (Consulta ID: $idConsulta) do Paciente: $paciente->nomePaciente");
    }

    /**
     * Gera o PDF de Receita Médica.
     */
    public function gerarPdfReceita($idConsulta)
    {
        $consulta = Consulta::findOrFail($idConsulta);
        $paciente = $consulta->paciente;

        return response("PDF de Receita para (Consulta ID: $idConsulta) do Paciente: $paciente->nomePaciente");
    }

    /**
     * Remove uma consulta (soft delete)
     */
    public function destroy($idConsulta) 
    {
        $consulta = Consulta::findOrFail($idConsulta);
        $medico = Auth::user()->medico;
         if (!$medico) {
             return redirect()->route('medico.prontuario')->with('error', 'Médico não encontrado.');
         }

        if ($consulta->idMedicoFK !== $medico->idMedicoPK) {
             return redirect()
                 ->route('medico.prontuario')
                 ->with('error', 'Você não tem permissão para excluir esta consulta.');
         }

        $pacienteId = null;
        if($consulta->prontuario) {
            $pacienteId = $consulta->prontuario->idPacienteFK;
        } elseif ($consulta->paciente) {
             $pacienteId = $consulta->paciente->idPaciente;
        }
        
        $consulta->delete();

        if ($pacienteId) {
             return redirect()
                ->route('medico.visualizarProntuario', $pacienteId)
                ->with('success', 'Consulta excluída com sucesso!');
        }
        
         return redirect()
             ->route('medico.prontuario')
             ->with('success', 'Consulta excluída com sucesso!');
    }
}