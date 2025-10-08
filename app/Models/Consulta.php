<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consulta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbConsulta';
    protected $primaryKey = 'idConsultaPK';

    protected $fillable = [
        'idProntuarioFK',
        'idMedicoFK',
        'nomeMedico',
        'crmMedico',
        'idEnfermeiroFK',
        'idUnidadeFK',
        'unidade',
        'dataConsulta',
        'observacoes',
        'examesSolicitados',
        'medicamentosPrescritos',
    ];

    protected $casts = [
        'dataConsulta' => 'date',
    ];

    /**
     * Relacionamento com Prontuário
     */
    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class, 'idProntuarioFK', 'idProntuarioPK');
    }

    /**
     * Relacionamento com Paciente (através do Prontuário)
     */
    public function paciente()
    {
        return $this->hasOneThrough(
            Paciente::class,
            Prontuario::class,
            'idProntuarioPK',      // FK na tabela prontuario
            'idPaciente',           // FK na tabela paciente
            'idProntuarioFK',       // Local key na tabela consulta
            'idPacienteFK'          // Local key na tabela prontuario
        );
    }

    /**
     * Relacionamento com Médico
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'idMedicoFK', 'idMedicoPK');
    }

    /**
     * Relacionamento com Enfermeiro
     */
    public function enfermeiro()
    {
        return $this->belongsTo(Enfermeiro::class, 'idEnfermeiroFK', 'idEnfermeiroPK');
    }

    /**
     * Relacionamento com Unidade
     */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'idUnidadeFK', 'idUnidadePK');
    }
}