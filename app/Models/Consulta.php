<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon; 

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
        'idPacienteFK', 
        'idRecepcionistaFK',
        'queixa_principal',
        'classificacao_risco',
        'status_atendimento',
    ];

    protected $casts = [
        'dataConsulta' => 'datetime',
    ];

    /**
     * Relacionamento com Prontuário
     */
    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class, 'idProntuarioFK', 'idProntuarioPK');
    }

    /**
     * Relacionamento com Paciente
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idPacienteFK', 'idPaciente');
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

    /**
     * Relacionamento com Recepcionista
     */
    public function recepcionista()
    {
        return $this->belongsTo(Recepcionista::class, 'idRecepcionistaFK', 'idRecepcionistaPK');
    }

    /**
     * 🔗 RELAÇÃO COM EXAMES 
     */
    public function exames()
    {
        return $this->hasMany(Exame::class, 'idConsultaFK', 'idConsultaPK');
    }

    /**
     * 🔗 RELAÇÃO COM MEDICAMENTOS 
     */
    public function medicamentos()
    {
        return $this->hasMany(Medicamento::class, 'idConsultaFK', 'idConsultaPK');
    }

    public function setDataConsultaAttribute($value)
    {
        $date = $value ? Carbon::parse($value) : Carbon::now('America/Sao_Paulo');
        $this->attributes['dataConsulta'] = $date->format('Y-m-d H:i:s');
    }
}