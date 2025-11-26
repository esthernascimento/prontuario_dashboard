<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Unidade;

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
        'idUnidadeFK',
        'statusAtivoRecepcionista', // <-- ADICIONADO
    ];

    protected $hidden = [ 'senhaRecepcionista', 'remember_token' ];

    public function getAuthPassword()
    {
        return $this->senhaRecepcionista;
    }

    /**
     * RELAÇÃO ADICIONADA: Um Recepcionista pertence a uma Unidade
     */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'idUnidadeFK', 'idUnidadePK');
    }
}