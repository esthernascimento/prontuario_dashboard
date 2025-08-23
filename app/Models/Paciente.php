<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'tbPaciente';
    protected $primaryKey = 'idPacientePK';
    public $timestamps = true;
    const CREATED_AT = 'dataCadastroPaciente';
    const UPDATED_AT = 'dataAtualizacaoPaciente';

    protected $fillable = [
        'nomePaciente',
        'cpfPaciente',
        'cartaoSusPaciente',
        'dataNascPaciente',
        'logradouroPaciente',
        'cidadePaciente',
        'ufPaciente',
        'cepPaciente',
        'alergiasPaciente',
        'id_usuarioFK',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuarioFK', 'idUsuarioPK');
    }
}