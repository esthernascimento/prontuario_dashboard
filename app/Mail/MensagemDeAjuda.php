<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MensagemDeAjuda extends Mailable
{
    use Queueable, SerializesModels;

    public $dados;

    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->dados['email'], $this->dados['nome']),
            subject: 'Nova Dúvida: ' . $this->dados['assunto'],
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.ajuda', // Nome da view do e-mail
            with: [
                'assunto' => $this->dados['assunto'],
                'mensagem' => $this->dados['mensagem'],
                'nomeUsuario' => $this->dados['nome'],
                'emailUsuario' => $this->dados['email'],
            ],
        );
    }
}