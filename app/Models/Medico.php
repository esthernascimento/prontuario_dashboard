<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importa o SoftDeletes

class Medico extends Model
{
    use HasFactory, SoftDeletes; // Usa o SoftDeletes

    protected $table = 'tbMedico';
    protected $primaryKey = 'idMedicoPK';
    public $timestamps = true; // Assumindo que você quer timestamps para tbMedico
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Coluna para o SoftDeletes
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
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
