<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recepcionista extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'tbRecepcionista';
    protected $primaryKey = 'idRecepcionistaPK';
    
    public $timestamps = true; 

    protected $fillable = [
        'nomeRecepcionista',
        'emailRecepcionista',
        'senhaRecepcionista',
        'idUnidadeFK', // 🔥 CORREÇÃO: Mudei para idUnidadeFK
    ];

    protected $hidden = [
        'senhaRecepcionista',
        'remember_token',
    ];

    /**
     * Diz ao Laravel qual é a coluna da senha.
     */
    public function getAuthPassword()
    {
        return $this->senhaRecepcionista;
    }

    /**
     * 🔥 CORREÇÃO: Define a relação: Um Recepcionista pertence a uma Unidade.
     */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'idUnidadeFK', 'idUnidadePK');
    }

    /**
     * 🔥 ADICIONEI: Campo usado para login (email)
     */
    public function getAuthIdentifierName()
    {
        return 'emailRecepcionista';
    }
}   