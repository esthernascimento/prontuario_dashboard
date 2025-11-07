<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensagemDeAjuda;
use App\Http\Controllers\Controller;

class SuporteController extends Controller
{
    public function enviarMensagem(Request $request)
    {
        $request->validate([
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string',
        ]);
        
        $admin = auth()->guard('admin')->user();
        
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Você precisa estar logado para enviar uma mensagem de suporte.');
        }

        if (empty($admin->emailUsuario) || empty($admin->nomeUsuario)) {
            return back()->with('error', 'O e-mail e o nome do seu perfil não estão cadastrados. Por favor, complete seu cadastro antes de enviar uma mensagem.');
        }

        $dados = [
            'nome' => $admin->nomeUsuario,
            'email' => $admin->emailUsuario,
            'assunto' => $request->input('assunto'),
            'mensagem' => $request->input('mensagem'),
        ];
        
        try {
            Mail::to('admwisys@gmail.com')->send(new MensagemDeAjuda($dados));
            
            return back()->with('success', 'Sua mensagem foi enviada com sucesso! Em breve entraremos em contato.');
        } catch (\Exception $e) {
            
            return back()->with('error', 'Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente mais tarde.');
        }
    }
}