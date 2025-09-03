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

    // Desabilita os timestamps padrão para usar colunas personalizadas.
    public $timestamps = false; 

    // O SoftDeletes requer a coluna deleted_at, que deve ser tratada como data.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'foto',
        'nomeMedico',
        'crmMedico',
        'especialidadeMedico',
        'id_usuarioFK',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuarioFK', 'idUsuarioPK');
    }
}