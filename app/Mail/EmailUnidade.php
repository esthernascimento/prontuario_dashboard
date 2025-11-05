<?php

namespace App\Mail;

use App\Models\Unidade;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUnidade extends Mailable
{
    use Queueable, SerializesModels;

    public $unidade;
    public $senhaTemporaria;

    public function __construct(Unidade $unidade, $senhaTemporaria)
    {
        $this->unidade = $unidade;
        $this->senhaTemporaria = $senhaTemporaria;
    }

    public function build()
    {
        return $this->subject('Bem-vindo ao sistema — Acesso da Unidade')
            ->view('emails.emailUnidade');
    }
}
