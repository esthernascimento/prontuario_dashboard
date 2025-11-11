<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exame extends Model
{
    use SoftDeletes;

    protected $table = 'tbExame';
    protected $primaryKey = 'idExamePK';

    protected $fillable = [
        'idConsultaFK',
        'idPacienteFK',
        'idProntuarioFK',
        'idMedicoFK',
        'idUnidadeFK',
        'nomeExame',
        'tipoExame',
        'descExame',
        'resultadoExame',
        'dataExame',
        'statusExame'
    ];

    protected $dates = ['deleted_at', 'dataExame'];

    /**
     * 🔗 Relacionamento com o médico solicitante
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'idMedicoFK', 'idMedicoPK');
    }

    /**
     * 🔗 Relacionamento com o paciente
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idPacienteFK', 'idPaciente');
    }

    /**
     * 🔗 Relacionamento com a unidade
     */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'idUnidadeFK', 'idUnidadePK');
    }

    /**
     * 🔗 Relacionamento com a consulta (opcional)
     */
    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'idConsultaFK', 'idConsultaPK');
    }

    /**
     * 🧩 Accessors para compatibilidade com o controller
     */
    public function getDescricaoAttribute()
    {
        return $this->descExame;
    }

    public function getResultadoAttribute()
    {
        return $this->resultadoExame;
    }
}
