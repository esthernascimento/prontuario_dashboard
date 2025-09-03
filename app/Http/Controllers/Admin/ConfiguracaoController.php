<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracaoController extends Controller
{
    public function perfil()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.perfilAdmin', compact('admin'));
    }

    public function atualizarPerfil(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'nomeAdmin' => 'required|string|max:255',
            'emailAdmin' => 'required|email|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        $admin->nomeAdmin = $request->nomeAdmin;
        $admin->emailAdmin = $request->emailAdmin;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('public/fotos');
            $admin->foto = basename($fotoPath);
        }

        $admin->save();

        return redirect()->route('admin.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}
