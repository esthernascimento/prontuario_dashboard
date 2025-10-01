<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importante: mudou para Authenticatable
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'tbPaciente';
    protected $primaryKey = 'idPacientePK';
    
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
        'emailPaciente',    
        'senhaPaciente',    
        'statusPaciente',
    ];

    /**
     * Atributos que devem ser ocultados.
     */
    protected $hidden = [
        'senhaPaciente',
    ];

    /**
     * Diz ao Laravel qual é a coluna da senha.
     */
    public function getAuthPassword()
    {
        return $this->senhaPaciente;
    }

    /**
     * Define a relação: Um Paciente tem um Prontuário.
     */
    public function prontuario()
    {
        return $this->hasOne(Prontuario::class, 'idPacienteFK', 'idPacientePK');
    }

    /**
     * Define a relação: Um Paciente pode ter muitas Alergias.
     */
    public function alergias()
    {
        return $this->hasMany(Alergia::class, 'idPacienteFK', 'idPacientePK');
    }
}

