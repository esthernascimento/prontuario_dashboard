<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prontuario;
use App\Models\Paciente; // Importe o Model Paciente
use Illuminate\Support\Carbon; // Import Carbon
use Illuminate\Support\Facades\Log; // Import Log facade for debugging

class ProntuarioController extends Controller
{
    /**
     * Busca os dados do prontuário para o AJAX.
     */
    public function buscar(Request $request)
    {
        // Valida se o paciente_id foi enviado
        $dadosValidados = $request->validate([
            'paciente_id' => 'required|exists:tbPaciente,idPaciente',
        ]);

        try {
            $pacienteId = $dadosValidados['paciente_id'];

            // 1. Encontra ou cria o prontuário
            $prontuario = Prontuario::firstOrCreate(
                ['idPacienteFK' => $pacienteId],
                ['dataAbertura' => now()] // Define a data de abertura se for um novo
            );

            // Garante que o prontuário foi encontrado ou criado
            if (!$prontuario) {
                 throw new \Exception('Não foi possível encontrar ou criar o prontuário.');
            }

            // 2. Carrega o paciente relacionado para pegar o status
            // Adiciona verificação se a relação existe e foi carregada
            $prontuario->load('paciente');
            $paciente = $prontuario->paciente; // Pega o modelo do paciente relacionado

            // 3. Define o status
            // Verifica se o paciente foi carregado e se a coluna de status existe
            // **IMPORTANTE: Confirme se o nome da coluna de status em tbPaciente é realmente 'status'**
            // Se for diferente (ex: 'statusPaciente'), troque '->status' abaixo pelo nome correto.
            $statusPaciente = 'INATIVO'; // Status padrão
            if ($paciente && isset($paciente->status)) { // <-- VERIFIQUE O NOME 'status' AQUI
                 // Use o status do paciente se ele existir
                 $statusPaciente = $paciente->status; // <-- E AQUI
            } elseif ($paciente) {
                 // Loga um aviso se o paciente existe mas a coluna 'status' não foi encontrada
                 Log::warning("Coluna 'status' não encontrada ou nula no Paciente ID: " . optional($paciente)->idPaciente); // Usando optional() para segurança
                 // Você pode definir um status padrão ou tratar de outra forma
                 $statusPaciente = 'Status Desconhecido';
            } else {
                 Log::warning("Relação 'paciente' não carregada para Prontuario ID: " . $prontuario->idProntuarioPK);
                 $statusPaciente = 'Paciente Não Encontrado';
            }


            // 4. Formata a data de abertura (assume que está cast no Model Prontuario)
            $dataAberturaFormatada = 'N/A';
            if ($prontuario->dataAbertura) {
                 // Verifica se já é Carbon, senão tenta parsear
                 $dataAbertura = $prontuario->dataAbertura instanceof Carbon ? $prontuario->dataAbertura : Carbon::parse($prontuario->dataAbertura);
                 $dataAberturaFormatada = $dataAbertura->format('d/m/Y');
            }

            // 5. Conta as consultas (assume relação 'consultas' no Model Prontuario)
            $totalConsultas = 0;
            // Verifica se a relação existe antes de chamar count()
            if (method_exists($prontuario, 'consultas')) {
                $totalConsultas = $prontuario->consultas()->count();
            } else {
                Log::warning("Relação 'consultas' não encontrada no Model Prontuario.");
            }


            // 6. Retorna os dados como JSON para o JavaScript
            return response()->json([
                'success' => true,
                'prontuarioId' => $prontuario->idProntuarioPK,
                'status' => $statusPaciente,
                'dataAbertura' => $dataAberturaFormatada,
                'totalConsultas' => $totalConsultas
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
             // Trata erros de validação especificamente
             Log::error('Erro de validação ao buscar prontuário: ' . $e->getMessage(), $e->errors());
             return response()->json(['success' => false, 'message' => 'Dados inválidos.', 'errors' => $e->errors()], 422);
        }
        catch (\Exception $e) {
            // Loga o erro detalhado para depuração no backend
            Log::error('Erro ao buscar prontuário: ' . $e->getMessage() . ' no arquivo ' . $e->getFile() . ' na linha ' . $e->getLine());

            // Retorna um erro genérico em JSON para o frontend (para não expor detalhes)
            return response()->json(['success' => false, 'message' => 'Ocorreu um erro interno ao buscar o prontuário.'], 500);
        }
    }
}

