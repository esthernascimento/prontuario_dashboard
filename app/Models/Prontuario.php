<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prontuario extends Model
{
    use HasFactory;

    protected $table = 'tbProntuario';
    protected $primaryKey = 'idProntuarioPK';

    protected $fillable = [
        'idPacienteFK',
        'dataAbertura',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idPacienteFK', 'idPacientePK');
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'idProntuarioFK');
    }
}

