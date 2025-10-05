<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbUnidade';
    protected $primaryKey = 'idUnidadePK';

    protected $fillable = [
        'nomeUnidade',
        'tipoUnidade',
        'enderecoUnidade',
    ];

   
    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'tbMedicoUnidade', 'idUnidadeFK', 'idMedicoFK');
    }

   
    public function enfermeiros()
    {
        return $this->belongsToMany(Enfermeiro::class, 'tbEnfermeiroUnidade', 'idUnidadeFK', 'idEnfermeiroFK');
    }
}

