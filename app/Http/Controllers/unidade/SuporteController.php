<?php

namespace App\Http\Controllers\unidade;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensagemDeAjuda;
use App\Http\Controllers\Controller;

class SuporteController extends Controller
{
    public function enviarMensagem(Request $request)
    {
        // Validação básica do formulário
        $request->validate([
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string',
        ]);
        
        // --- NOVO: VERIFICAÇÃO DO USUÁRIO LOGADO ---
        $admin = auth()->guard('admin')->user();
        
        if (!$admin) {
            // Se o usuário não está logado, redirecione-o para a página de login
            return redirect()->route('admin.login')->with('error', 'Você precisa estar logado para enviar uma mensagem de suporte.');
        }

        // --- NOVO: VERIFICAÇÃO DAS PROPRIEDADES DO USUÁRIO ---
        if (empty($admin->emailUsuario) || empty($admin->nomeUsuario)) {
            // Se as propriedades estiverem vazias, retorne um erro
            return back()->with('error', 'O e-mail e o nome do seu perfil não estão cadastrados. Por favor, complete seu cadastro antes de enviar uma mensagem.');
        }

        // Coletar os dados para o e-mail
        $dados = [
            'nome' => $admin->nomeUsuario,
            'email' => $admin->emailUsuario,
            'assunto' => $request->input('assunto'),
            'mensagem' => $request->input('mensagem'),
        ];
        
        try {
            // Tentar enviar o e-mail
            Mail::to('admwisys@gmail.com')->send(new MensagemDeAjuda($dados));
            
            // Redirecionar com mensagem de sucesso
            return back()->with('success', 'Sua mensagem foi enviada com sucesso! Em breve entraremos em contato.');
        } catch (\Exception $e) {
            // Se ocorrer um erro no envio, retorne uma mensagem de erro
            return back()->with('error', 'Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente mais tarde.');
        }
    }
}