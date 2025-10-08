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

    protected $casts = [
        'dataAbertura' => 'date',
    ];

    /**
     * Relacionamento com Paciente (1:1)
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idPacienteFK', 'idPaciente');
    }

    /**
     * Relacionamento com Consultas (1:N)
     * Um prontuário tem várias consultas
     */
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'idProntuarioFK', 'idProntuarioPK')
                    ->orderBy('dataConsulta', 'desc');
    }

    /**
     * Retorna a última consulta registrada
     */
    public function ultimaConsulta()
    {
        return $this->hasOne(Consulta::class, 'idProntuarioFK', 'idProntuarioPK')
                    ->latest('dataConsulta');
    }

    /**
     * Retorna o total de consultas do prontuário
     */
    public function getTotalConsultasAttribute()
    {
        return $this->consultas()->count();
    }
}