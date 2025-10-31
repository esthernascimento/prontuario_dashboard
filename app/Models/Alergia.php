<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Alergia extends Model
{
    protected $table = 'tbAlergia';
    protected $primaryKey = 'idAlergiaPK';
    protected $fillable = [
        'idPacienteFK',
        'nomeAlergia',
        'tipoAlergia',
        'severidadeAlergia',
        'descAlergia',
    ];
}