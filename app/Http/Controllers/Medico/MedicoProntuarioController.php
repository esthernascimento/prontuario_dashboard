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
use Illuminate\Support\Facades\DB; // Importar DB para a transaction

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
            ->whereNotNull('idMedicoFK') 
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
            // (Tratamento de erro)
        }

        $medico = Auth::user()->medico;
         if (!$medico) {
            // (Tratamento de erro)
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

        DB::transaction(function () use ($consulta, $paciente, $validated, $prontuario) {
            $consulta->save(); // Salva a consulta para obter o idConsultaPK

            // Inserção de medicamentos
            if (!empty($validated['medicamentosPrescritos'])) {
                $linhas = collect(preg_split('/\r?\n/', $validated['medicamentosPrescritos']))
                    ->map(fn($l) => trim($l))
                    ->filter()
                    ->filter(fn($l) => preg_match('/[a-zA-ZÀ-ÿ]/', $l) && strlen($l) > 2 && strlen($l) < 255); // Filtro simplificado
                    
                foreach ($linhas as $linha) {
                    Medicamento::create([
                        'idConsultaFK' => $consulta->idConsultaPK,
                        'idProntuarioFK' => $prontuario->idProntuarioPK,
                        'idPacienteFK' => $paciente->idPaciente, // Correto
                        'descMedicamento' => $linha,
                        'nomeMedicamento' => $linha,
                    ]);
                }
            }

            // Inserção de exames
            if (!empty($validated['examesSolicitados'])) {
                $linhas = collect(preg_split('/\r?\n/', $validated['examesSolicitados']))
                    ->map(fn($l) => trim($l))
                    ->filter()
                    ->filter(fn($l) => preg_match('/[a-zA-ZÀ-ÿ]/', $l) && strlen($l) > 2 && strlen($l) < 255); // Filtro simplificado
                    
                foreach ($linhas as $linha) {
                    // (Lógica de parsing de nome/descrição)
                    $nomeExame = $linha;
                    $descExame = $linha;
                    if (strpos($linha, ':') !== false) {
                         $partes = explode(':', $linha, 2); $nomeExame = trim($partes[0]); $descExame = trim($partes[1]) ?: $nomeExame;
                    } elseif (strpos($linha, ' - ') !== false) {
                         $partes = explode(' - ', $linha, 2); $nomeExame = trim($partes[0]); $descExame = trim($partes[1]) ?: $nomeExame;
                    } elseif (strlen($linha) > 50) {
                         $palavras = explode(' ', $linha); $nomeExame = implode(' ', array_slice($palavras, 0, 3));
                    }
                    
                    Exame::create([
                        'idConsultaFK' => $consulta->idConsultaPK,
                        'nomeExame' => $nomeExame,
                        'descExame' => $descExame,
                        'dataExame' => $consulta->dataConsulta?->toDateString() ?? now()->toDateString(),

                        // --- CORREÇÃO ADICIONADA ---
                        'idPacienteFK' => $paciente->idPaciente,
                        'idProntuarioFK' => $prontuario->idProntuarioPK,
                        // --- FIM DA CORREÇÃO ---
                    ]);
                }
            }
        });

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
            $consulta->save(); // Salva a consulta com o novo idProntuarioFK
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
        $consulta->examesSolicitados = $validated['examesSolicitados'] ?? null; // Texto completo
        $consulta->medicamentosPrescritos = $validated['medicamentosPrescritos'] ?? null; // Texto completo

        if (!$consulta->idProntuarioFK && $consulta->paciente) {
             $prontuario = Prontuario::firstOrCreate(
                ['idPacienteFK' => $consulta->paciente->idPaciente],
                ['dataAbertura' => now()->toDateString()]
             );
             $consulta->idProntuarioFK = $prontuario->idProntuarioPK;
        }

        DB::transaction(function () use ($consulta, $validated) {
            $consulta->save(); // Salva as atualizações na consulta (observacoes, etc.)

            // (Opcional: Limpar exames/medicamentos antigos antes de inserir novos)
            // Medicamento::where('idConsultaFK', $consulta->idConsultaPK)->delete();
            // Exame::where('idConsultaFK', $consulta->idConsultaPK)->delete();

            // Atualiza/inserções automáticas de Medicamentos
            if (!empty($validated['medicamentosPrescritos'])) {
                $linhas = collect(preg_split('/\r?\n/', $validated['medicamentosPrescritos']))
                    ->map(fn($l) => trim($l))
                    ->filter()
                    ->filter(fn($l) => preg_match('/[a-zA-ZÀ-ÿ]/', $l) && strlen($l) > 2 && strlen($l) < 255); // Filtro
                    
                foreach ($linhas as $linha) {
                    Medicamento::firstOrCreate([
                        // Chaves para buscar
                        'idConsultaFK' => $consulta->idConsultaPK,
                        'descMedicamento' => $linha,
                    ], [
                        // Valores para criar (se não encontrar)
                        'idPacienteFK' => $consulta->idPacienteFK,
                        'idProntuarioFK' => $consulta->idProntuarioFK,
                        'nomeMedicamento' => $linha,
                    ]);
                }
            }

            // Atualiza/inserções automáticas de Exames
            if (!empty($validated['examesSolicitados'])) {
                $linhas = collect(preg_split('/\r?\n/', $validated['examesSolicitados']))
                    ->map(fn($l) => trim($l))
                    ->filter()
                    ->filter(fn($l) => preg_match('/[a-zA-ZÀ-ÿ]/', $l) && strlen($l) > 2 && strlen($l) < 255); // Filtro
                    
                foreach ($linhas as $linha) {
                    // (Lógica de parsing)
                    $nomeExame = $linha;
                    $descExame = $linha;
                     if (strpos($linha, ':') !== false) {
                         $partes = explode(':', $linha, 2); $nomeExame = trim($partes[0]); $descExame = trim($partes[1]) ?: $nomeExame;
                    } elseif (strpos($linha, ' - ') !== false) {
                         $partes = explode(' - ', $linha, 2); $nomeExame = trim($partes[0]); $descExame = trim($partes[1]) ?: $nomeExame;
                    } elseif (strlen($linha) > 50) {
                         $palavras = explode(' ', $linha); $nomeExame = implode(' ', array_slice($palavras, 0, 3));
                    }

                    Exame::firstOrCreate([
                        // Chaves para buscar
                        'idConsultaFK' => $consulta->idConsultaPK,
                        'descExame' => $descExame,
                        'dataExame' => $consulta->dataConsulta?->toDateString() ?? now()->toDateString(),
                    ], [
                        // Valores para criar (se não encontrar)
                        'nomeExame' => $nomeExame,
                        
                        // --- CORREÇÃO ADICIONADA ---
                        'idPacienteFK' => $consulta->idPacienteFK,
                        'idProntuarioFK' => $consulta->idProntuarioFK,
                        // --- FIM DA CORREÇÃO ---
                    ]);
                }
            }
        });

        return redirect()
            ->route('medico.prontuario')
            ->with('success', 'Atendimento finalizado com sucesso!');
    }

    public function destroy($idConsulta)
    {
        
    }
}

