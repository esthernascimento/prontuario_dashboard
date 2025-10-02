<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'tbPaciente';
    protected $primaryKey = 'idPaciente';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'nomePaciente',
        'cpfPaciente',
        'dataNascPaciente',
        'cartaoSusPaciente',
        'generoPaciente',
        'fotoPaciente',
        'telefonePaciente',
        'logradouroPaciente',
        'numLogradouroPaciente',
        'cepPaciente',
        'bairroPaciente',
        'cidadePaciente',
        'ufPaciente',
        'estadoPaciente',
        'paisPaciente',
        'emailPaciente',
        'senhaPaciente',
        'statusPaciente',
    ];

    protected $hidden = [
        'senhaPaciente',
    ];

    protected $casts = [
        'statusPaciente'   => 'boolean',
        'dataNascPaciente' => 'date',
    ];

    // Usado pelo Guard/Auth para saber qual coluna é a senha
    public function getAuthPassword()
    {
        return $this->senhaPaciente;
    }

    /**
     * Relações (exemplos — ajuste os nomes das FKs conforme suas migrations)
     */
    public function prontuario()
    {
        // supondo que prontuarios.idPacienteFK -> tbPaciente.idPaciente
        return $this->hasOne(Prontuario::class, 'idPacienteFK', 'idPaciente');
    }

    public function alergias()
    {
        // supondo que alergias.idPacienteFK -> tbPaciente.idPaciente
        return $this->hasMany(Alergia::class, 'idPacienteFK', 'idPaciente');
    }

}
