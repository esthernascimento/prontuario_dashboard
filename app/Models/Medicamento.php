<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicamento extends Model
{
    use HasFactory;

    protected $table = 'tbMedicamento';
    protected $primaryKey = 'idMedicamentoPK';
    
    protected $fillable = [
        'idConsultaFK',
        'idPacienteFK',
        'idProntuarioFK',
        'descMedicamento',
        'tipoMedicamento',
        'nomeMedicamento',
        'dosagemMedicamento',
        'frequenciaMedicamento',
        'periodoMedicamento',
    ];

    /**
     * 🔗 Relacionamento com a consulta
     */
    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'idConsultaFK', 'idConsultaPK');
    }

    /**
     * 🔗 Relacionamento com o paciente
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idPacienteFK', 'idPaciente');
    }

    /**
     * 🔗 Relacionamento com o prontuário
     */
    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class, 'idProntuarioFK', 'idProntuarioPK');
    }
}