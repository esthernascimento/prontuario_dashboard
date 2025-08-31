<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbMedico';
    protected $primaryKey = 'idMedicoPK';
    
    // Desabilitado para evitar o erro de 'updated_at'
    public $timestamps = false; 

    protected $fillable = [
        'nomeMedico',
        'crmMedico',
        'especialidadeMedico',
        'id_usuarioFK',
    ];

    // Relação com o Usuário (um Médico pertence a um Usuário)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuarioFK', 'idUsuarioPK');
    }
}

