<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Prontuario;
use App\Models\Alergia;
use App\Models\AnotacaoEnfermagem;
use App\Models\Consulta;
use App\Models\Medicamento; 
use App\Models\Exame;      

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
        'remember_token', 
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
     * Relações
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

    public function anotacoesEnfermagem()
    {
        return $this->hasMany(AnotacaoEnfermagem::class, 'idPacienteFK', 'idPaciente');
    }
    
    public function consultas()
    {
        return $this->hasMany(\App\Models\Consulta::class, 'idPacienteFK', 'idPaciente');
    }

    public function medicamentos()
    {
        return $this->hasManyThrough(
            Medicamento::class, // Model Final
            Consulta::class,    // Model Intermediário
            'idPacienteFK',     // Chave estrangeira na tbConsulta (ligando a Paciente)
            'idConsultaFK',     // Chave estrangeira na tbMedicamento (ligando a Consulta)
            'idPaciente',       // Chave local na tbPaciente
            'idConsultaPK'      // Chave local na tbConsulta
        );
    }
    
    /**
     * Busca todos os exames solicitados ao paciente através de suas consultas.
     */
    public function exames()
    {
        return $this->hasManyThrough(
            Exame::class,       // Model Final
            Consulta::class,    // Model Intermediário
            'idPacienteFK',     // Chave estrangeira na tbConsulta (ligando a Paciente)
            'idConsultaFK',     // Chave estrangeira na tbExame (ligando a Consulta)
            'idPaciente',       // Chave local na tbPaciente
            'idConsultaPK'      // Chave local na tbConsulta
        );
    }
}