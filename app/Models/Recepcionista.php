<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Unidade; // Importar o Model Unidade

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
        'idUnidadeFK', // <-- CORRIGIDO (estava idAdminFK)
    ];

    protected $hidden = [ 'senhaRecepcionista', 'remember_token' ];

    public function getAuthPassword()
    {
        return $this->senhaRecepcionista;
    }

    /**
     * 🔥 CORREÇÃO: Relação com Unidade (1:N)
     * (Removidas as relações 'admin()' e 'unidades()' da tabela pivô)
     */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'idUnidadeFK', 'idUnidadePK');
    }
}