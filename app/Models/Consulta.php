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
        'idEnfermeiroFK',
        'idUnidadeFK',
        'dataConsulta',
        'obsConsulta',
    ];

    protected $casts = [
        'dataConsulta' => 'datetime',
    ];

    
    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class, 'idProntuarioFK', 'idProntuarioPK');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'idMedicoFK', 'idMedicoPK');
    }

    public function enfermeiro()
    {
        return $this->belongsTo(Enfermeiro::class, 'idEnfermeiroFK', 'idEnfermeiroPK');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'idUnidadeFK', 'idUnidadePK');
    }

    // --- RELACIONAMENTOS (hasMany) ---
    // Indica que esta consulta pode ter vários registos associados.

    public function medicamentos()
    {
        return $this->hasMany(Medicamento::class, 'idConsultaFK', 'idConsultaPK');
    }



    public function exames()
    {
        return $this->hasMany(Exame::class, 'idConsultaFK', 'idConsultaPK');
    }
}

