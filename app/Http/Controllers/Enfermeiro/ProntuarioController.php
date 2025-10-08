<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente; // Modelo para o paciente
use App\Models\AnotacaoEnfermagem; // Assumindo que você terá um modelo para as anotações
use Carbon\Carbon; // Para formatação de data (se necessário)
use Illuminate\Support\Facades\Auth; // Para obter o ID do enfermeiro logado

class ProntuarioController extends Controller
{
    /**
     * Exibe a lista de pacientes no dashboard do enfermeiro, com paginação.
     */
    public function index()
    {
        // 1. Busca todos os pacientes ordenados pelo nome
        // Usamos paginate(15) para limitar o número de resultados por página, melhorando a performance.
        $pacientes = Paciente::orderBy('nomePaciente')->paginate(15);
        
        // 2. Passa a lista de pacientes para a view
        return view('enfermeiro.prontuarioEnfermeiro', compact('pacientes'));
    }

    public function createAnotacao($id) 
{
        // 1. Busca o paciente. Assumindo que $id é o idPaciente.
        // Use firstOrFail() para retornar 404 se não achar, ou a sua função de busca.
        $paciente = Paciente::findOrFail($id); 

        // 2. Passa a variável para a view com o nome EXATO: 'paciente'
        return view('enfermeiro.cadastrarProntuarioEnfermeiro', compact('paciente')); 
        // OU: return view('enfermeiro.cadastrarProntuarioEnfermeiro', ['paciente' => $paciente]);
    }

    /**
     * Exibe o prontuário detalhado de um paciente (Histórico, Anotações, etc.)
     */
    public function show($id)
    {
        try {
        // 1. Busca o paciente. Usamos findOrFail, que já está correto.
        // O id usado aqui é a PK da tabela tbPaciente, que é 'idPaciente'.
        $paciente = Paciente::findOrFail($id);

        // 2. Busca as anotações de enfermagem.
        // Usamos o Model de AnotaçãoEnfermagem para buscar pelo ID do paciente.
        // ASSUMIMOS que a chave estrangeira na tabela de anotações se chama 'idPacienteFK'
        // (Baseado no padrão do seu Model Paciente).
        $anotacoes = AnotacaoEnfermagem::where('idPacienteFK', $paciente->idPaciente)
                                       ->orderBy('data_hora', 'desc') // Ordena pelas mais recentes
                                       ->get();

        // 3. Retorna a view, passando AMBAS as variáveis: $paciente e $anotacoes.
        // Isso resolve o erro "Undefined variable $anotacoes".
        return view('enfermeiro.visualizarProntuarioEnfermeiro', compact('paciente', 'anotacoes'));

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Se o Paciente não for encontrado
        return redirect()->route('enfermeiro.prontuario.index')->with('error', 'Paciente não encontrado.');
    }
    }

    // app/Http/Controllers/Enfermeiro/ProntuarioController.php (Exemplo)

    

    /**
     * Exibe o formulário para adicionar uma nova anotação de enfermagem a um paciente.
     */
    public function anotacao($id)
    {
        // 1. Encontrar o paciente pelo ID
    $paciente = Paciente::findOrFail($id); 

    // 2. Passar o objeto $paciente para a view
    return view('enfermeiro.visualizarProntuarioEnfermeiro', compact('paciente')); 
    }
    /**
     * Processa e salva a nova anotação de enfermagem no prontuário do paciente.
     */
    // No seu ProntuarioController.php

public function storeAnotacao(Request $request, $id)
{
    // ... (Validação e criação do registro)

    try {
        // ... (Criação da AnotaçãoEnfermagem)

        // CORREÇÃO CRÍTICA: Use o ID que chegou pela rota ($id) 
        // ou a chave primária do objeto $paciente, que é $paciente->idPaciente
        
        // Mantenha assim:
        return redirect()
            ->route('enfermeiro.prontuario.show', $id) // Usa o $id da URL, que é o idPaciente
            ->with('success', 'Anotação de enfermagem registrada com sucesso!');

    } catch (\Exception $e) {
        // Se houver qualquer falha (validação no DB, Model não configurado, etc.)
        // ele cai aqui e REDIRECIONA PARA TRÁS, parecendo que nada aconteceu!
        
        // Para debug, remova o `->back()` e tente redirecionar para o histórico 
        // com uma mensagem de ERRO (que você pode exibir no Blade):
        
        return redirect()
            ->route('enfermeiro.prontuario.show', $id) // Tenta redirecionar, mesmo com erro
            ->with('error', 'Erro DETALHADO: Falha ao salvar no banco de dados. Verifique o Model e o DB. Detalhe: ' . $e->getMessage());

        /* OU apenas volte com o input para a pessoa ver o erro */
        // return redirect()->back()->withInput()->with('error', 'Erro ao salvar...');
    }
}
}
