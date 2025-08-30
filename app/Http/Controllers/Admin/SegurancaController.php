<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SegurancaController extends Controller
{
    public function alterarSenha(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => 'required|min:8|confirmed',
        ], [
            'nova_senha.confirmed' => 'A confirmação da senha não corresponde.',
            'nova_senha.min' => 'A nova senha precisa ter no mínimo 8 caracteres.',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return back()->withErrors(['auth' => 'Não foi possível identificar o administrador logado.']);
        }

        if (!Hash::check($request->senha_atual, $admin->senhaAdmin)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        $admin->senhaAdmin = Hash::make($request->nova_senha);
        $admin->save();
        
        return back()->with('success', 'Senha alterada com sucesso!');
    }
}
