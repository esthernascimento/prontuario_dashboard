<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnotacaoEnfermagem extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'tbAnotacaoEnfermagem'; 
    protected $primaryKey = 'idAnotacao';
    
    protected $fillable = [
        'idPacienteFK',
        'idEnfermeiroFK',
        'data_hora',
        'tipo_registro',
        'unidade_atendimento',
        'descricao',
        'temperatura',
        'pressao_arterial',
        'frequencia_cardiaca',
        'saturacao',
        'frequencia_respiratoria',
        'dor',
        'alergias',
        'medicacoes_ministradas',
    ];
    
    protected $casts = [
        'data_hora' => 'datetime',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idPacienteFK', 'idPaciente');
    }

    public function enfermeiro()
    {
        return $this->belongsTo(Enfermeiro::class, 'idEnfermeiroFK', 'idEnfermeiroPK');
    }
    
    public function unidadeAtendimento()
    {
        return $this->belongsTo(Unidade::class, 'unidade_atendimento', 'idUnidadePK');
    }
}