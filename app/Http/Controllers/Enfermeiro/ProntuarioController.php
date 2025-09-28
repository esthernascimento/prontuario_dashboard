<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente; // << GARANTIR QUE ESTE MODEL EXISTA
use Carbon\Carbon; // Para formatação de data na view (se precisar)

class ProntuarioController extends Controller
{
    /**
     * Exibe a lista de pacientes no dashboard do enfermeiro.
     */
    public function index()
    {
        // 1. Busca todos os pacientes ordenados pelo nome
        // Você pode ajustar a query (where, paginate) conforme o volume de dados.
        // Se usar paginate(), lembre-se de usar $pacientes->links() na view.
        $pacientes = Paciente::orderBy('nome')->get();
        
        // Se a sua coluna de data de nascimento se chama 'data_nasc' e não 'data_nascimento', 
        // certifique-se de que a query e a view estejam consistentes.
        
        // 2. Passa a lista de pacientes para a view
        return view('enfermeiro.prontuario', compact('pacientes'));
    }

    /**
     * Exibe o prontuário detalhado de um paciente (se necessário)
     */
    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);

        return view('enfermeiro.prontuario_detalhe', compact('paciente'));
    }
}
