<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importe a trait

class Exame extends Model
{
    use SoftDeletes; // Adicione a trait aqui

    protected $table = 'tbExame';
    protected $primaryKey = 'idExamePK';

    // Ajuste dos campos fillable para refletir a estrutura da sua tabela
    protected $fillable = [
        'idConsultaFK', 
        'idPacienteFK', // Adicione se for um campo que pode ser preenchido
        'descExame',
        'resultadoExame',
        'dataExame',
    ];
    
    // Opcional: Define os campos de data
    protected $dates = ['deleted_at', 'dataExame'];
}