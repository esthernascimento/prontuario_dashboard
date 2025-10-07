<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;

class EmailMedico extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $senhaTemporaria;

    public function __construct(Usuario $usuario, string $senhaTemporaria)
    {
        $this->usuario = $usuario;
        $this->senhaTemporaria = $senhaTemporaria;
    }

    public function build()
    {
        return $this
            ->subject('Bem-vindo ao sistema — Acesso do Médico')
            ->view('emails.emailMedico');
    }
}
