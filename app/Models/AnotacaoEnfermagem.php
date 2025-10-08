<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnotacaoEnfermagem extends Model
{
    use HasFactory;
    
    // Assumindo que sua tabela de anotações se chama tbAnotacaoEnfermagem
    protected $table = 'tbAnotacaoEnfermagem'; 
    
    // Assumindo que a chave primária da tabela de anotações se chama idAnotacao
    protected $primaryKey = 'idAnotacao'; 

    protected $fillable = [
        'idPacienteFK',         // Chave estrangeira para o Paciente
        'idEnfermeiroFK',       // Chave estrangeira para o Enfermeiro (quem registrou)
        'data_hora',            // Data e hora do registro
        'tipo_registro',        // Ex: sinais_vitais, evolucao
        'unidade_atendimento',  // Local onde a anotação foi feita
        'descricao',            // O corpo principal da anotação
        'temperatura',          // Sinais Vitais (Opcional)
        'pressao_arterial',     // Sinais Vitais (Opcional)
        'frequencia_cardiaca',  // Sinais Vitais (Opcional)
        'saturacao',            // Sinais Vitais (Opcional)
        // Adicione outros campos conforme a sua migration
    ];
    
    protected $casts = [
        'data_hora' => 'datetime',
    ];

    /**
     * Relacionamento: Uma anotação pertence a um paciente.
     */
    public function paciente()
    {
        // Chave estrangeira no model atual (idPacienteFK) se relaciona com 
        // a chave primária do Paciente (idPaciente).
        return $this->belongsTo(Paciente::class, 'idPacienteFK', 'idPaciente');
    }

    /**
     * Relacionamento: Uma anotação foi feita por um enfermeiro.
     */
    public function enfermeiro()
    {
        // Assumindo que você tem um Model Enfermeiro
        // e que a FK se chama 'idEnfermeiroFK'.
        return $this->belongsTo(Enfermeiro::class, 'idEnfermeiroFK', 'idEnfermeiro');
    }
}