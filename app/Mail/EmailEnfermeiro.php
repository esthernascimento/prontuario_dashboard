<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;

class EmailEnfermeiro extends Mailable
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
        return $this->subject('Bem-vindo ao Prontuário+ — Acesso do Enfermeiro')
                    ->view('emails.emailEnfermeiro');
    }
}
