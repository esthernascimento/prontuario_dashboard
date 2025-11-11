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
        
        // --- ADICIONADO: Campos do novo fluxo ---
        'idPacienteFK', 
        'idRecepcionistaFK',
        'queixa_principal',
        'classificacao_risco',
        'status_atendimento',
        // --- FIM DA ADIÇÃO ---
    ];

    protected $casts = [
        'dataConsulta' => 'datetime', // Use datetime se incluir hora, ou date se for só data
    ];

    /**
     * Relacionamento com Prontuário
     */
    public function prontuario()
    {
        // Chave estrangeira 'idProntuarioFK' na tbConsulta
        // Chave primária 'idProntuarioPK' na tbProntuario
        return $this->belongsTo(Prontuario::class, 'idProntuarioFK', 'idProntuarioPK');
    }

    /**
     * Relacionamento com Paciente (Ligação Direta)
     * --- FUNÇÃO CORRIGIDA ---
     */
    public function paciente()
    {
        // Chave estrangeira 'idPacienteFK' na tbConsulta
        // Chave primária 'idPaciente' na tbPaciente (Confirmar se é 'idPaciente' ou 'idPacientePK')
        return $this->belongsTo(Paciente::class, 'idPacienteFK', 'idPaciente'); 
    }

    /**
     * Relacionamento com Médico
     */
    public function medico()
    {
         // Chave estrangeira 'idMedicoFK' na tbConsulta
         // Chave primária 'idMedicoPK' na tbMedico
        return $this->belongsTo(Medico::class, 'idMedicoFK', 'idMedicoPK');
    }

    /**
     * Relacionamento com Enfermeiro
     */
    public function enfermeiro()
    {
         // Chave estrangeira 'idEnfermeiroFK' na tbConsulta
         // Chave primária 'idEnfermeiroPK' na tbEnfermeiro
        return $this->belongsTo(Enfermeiro::class, 'idEnfermeiroFK', 'idEnfermeiroPK');
    }

    /**
     * Relacionamento com Unidade
     */
    public function unidade()
    {
         // Chave estrangeira 'idUnidadeFK' na tbConsulta
         // Chave primária 'idUnidadePK' na tbUnidade
        return $this->belongsTo(Unidade::class, 'idUnidadeFK', 'idUnidadePK');
    }

    /**
     * Relacionamento com Recepcionista
     * --- ADICIONADO ---
     */
    public function recepcionista()
    {
         // Chave estrangeira 'idRecepcionistaFK' na tbConsulta
         // Chave primária 'idRecepcionistaPK' na tbRecepcionista
        return $this->belongsTo(Recepcionista::class, 'idRecepcionistaFK', 'idRecepcionistaPK');
    }


        public function setDataConsultaAttribute($value)
    {
  
        $date = $value ? Carbon::parse($value) : Carbon::now('America/Sao_Paulo');
        
        $this->attributes['dataConsulta'] = $date->format('Y-m-d H:i:s');
    }
}

