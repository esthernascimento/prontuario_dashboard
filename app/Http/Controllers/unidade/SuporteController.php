<?php

namespace App\Http\Controllers\Unidade;

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
        
        $unidade = auth()->guard('unidade')->user();
        
        if (!$unidade) {
            return redirect()->route('unidade.login')->with('error', 'Você precisa estar logado para enviar uma mensagem de suporte.');
        }

        if (empty($unidade->emailUnidade) || empty($unidade->nomeUnidade)) {
            return back()->with('error', 'O e-mail e o nome do seu perfil não estão cadastrados. Por favor, complete seu cadastro antes de enviar uma mensagem.');
        }

        $dados = [
            'nome' => $unidade->nomeUnidade,
            'email' => $unidade->emailUnidade,
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