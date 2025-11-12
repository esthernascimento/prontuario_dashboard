<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // MUDANÇA IMPORTANTE
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Authenticatable // MUDANÇA IMPORTANTE
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'tbUsuario';
    protected $primaryKey = 'idUsuarioPK';
    public $timestamps = true;
    const CREATED_AT = 'dataCadastroUsuario';
    const UPDATED_AT = 'dataAtualizacaoUsuario';

    protected $fillable = [
        'nomeUsuario',
        'emailUsuario',
        'senhaUsuario',
        'statusAtivoUsuario',
        'foto',
        'statusSenhaUsuario',

    ];

    protected $hidden = [
        'senhaUsuario',
    ];

    // Relacionamentos
    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'id_usuarioFK', 'idUsuarioPK');
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'id_usuarioFK', 'idUsuarioPK');
    }

    // Método para o sistema de autenticação saber qual coluna é a da senha
    public function getAuthPassword()
    {
        return $this->senhaUsuario;
    }
}

