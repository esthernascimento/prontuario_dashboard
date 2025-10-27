<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Usa Authenticatable para login
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recepcionista extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'tbRecepcionista';
    protected $primaryKey = 'idRecepcionistaPK';
    
    // Assume que a tabela terá created_at e updated_at
    public $timestamps = true; 

    protected $fillable = [
        'nomeRecepcionista',
        'emailRecepcionista',
        'senhaRecepcionista',
        'idAdminFK',
    ];

    protected $hidden = [
        'senhaRecepcionista',
        'remember_token', // --- ADICIONADO --- (Boa prática do Laravel)
    ];

    /**
     * Diz ao Laravel qual é a coluna da senha.
     */
    public function getAuthPassword()
    {
        return $this->senhaRecepcionista;
    }

    /**
     * Define a relação: Um Recepcionista pertence a um Admin.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'idAdminFK', 'idAdminPK');
    }

    // No futuro, podemos adicionar a relação com as Consultas que ele iniciou
    // public function consultasIniciadas()
    // {
    //     return $this->hasMany(Consulta::class, 'idRecepcionistaFK', 'idRecepcionistaPK');
    // }
}
