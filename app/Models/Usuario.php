<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes; 

    protected $table = 'tbUsuario';
    protected $primaryKey = 'idUsuarioPK';
    public $timestamps = true;
    const CREATED_AT = 'dataCadastroUsuario';
    const UPDATED_AT = 'dataAtualizacaoUsuario';

    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'nomeUsuario',
        'emailUsuario',
        'senhaUsuario',
        'statusAtivoUsuario',
    ];

    protected $hidden = [
        'senhaUsuario',
    ];

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'id_usuarioFK', 'idUsuarioPK');
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'id_usuarioFK', 'idUsuarioPK');
    }

    public function getAuthPassword()
    {
        return $this->senhaUsuario;
    }
}
