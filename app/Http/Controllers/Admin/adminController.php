<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Exibe o dashboard do admin
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Exibe configurações de segurança
    public function seguranca()
    {
        return view('admin.seguranca');
    }

    // Exibe configurações gerais
    public function configuracoes()
    {
        return view('admin.configuracoes');
    }

    // Atualiza senha do admin
    public function alterarSenha(Request $request)
    {
        // lógica de alteração de senha
    }

    // Atualiza perfil do admin
    public function atualizarPerfil(Request $request)
    {
        // lógica de atualização de perfil
    }
}
