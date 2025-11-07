<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function seguranca()
    {
        return view('admin.seguranca');
    }

    public function configuracoes()
    {
        return view('admin.configuracoes');
    }

    public function alterarSenha(Request $request)
    {
    }

    public function atualizarPerfil(Request $request)
    {
    }
}
