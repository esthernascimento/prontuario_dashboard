<?php

namespace App\Http\Controllers\unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    /**
     * Exibe o dashboard da unidade (dashboardUnidade.blade.php).
     */
    public function dashboard()
    {
        // Usando 'dashboardUnidade' conforme o nome do arquivo na imagem.
        return view('unidade.dashboardUnidade');
    }

    /**
     * Exibe a página de Ajuda (ajuda.blade.php).
     */
    public function ajuda()
    {
        return view('unidade.ajuda');
    }

    /**
     * Exibe a página de Perfil da Unidade (perfilUnidade.blade.php).
     */
    public function perfilUnidade()
    {
        return view('unidade.perfilUnidade');
    }

    /**
     * Exibe o formulário de Cadastro de Novo Admin (cadastroAdm.blade.php).
     */
    public function cadastroAdm()
    {
        return view('unidade.cadastroAdm');
    }

    /**
     * Exibe as configurações de segurança (seguranca.blade.php).
     */
    public function seguranca()
    {
        return view('unidade.seguranca');
    }

    /*
     * MÉTODOS DE AÇÃO - Não precisam retornar views, apenas processar dados e redirecionar.
     * =================================================================================
     */

    /**
     * Processa a atualização da senha do admin.
     */
    public function alterarSenha(Request $request)
    {
        // lógica de alteração de senha
        // return redirect()->route('rota.do.perfil')->with('success', 'Senha atualizada!');
    }

    /**
     * Processa a atualização do perfil do admin/unidade.
     */
    public function atualizarPerfil(Request $request)
    {
        // lógica de atualização de perfil
        // return redirect()->route('rota.do.perfil')->with('success', 'Perfil atualizado!');
    }
}
