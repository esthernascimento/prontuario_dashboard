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

    /**
     * Os atributos que podem ser preenchidos em massa,
     * agora incluindo todos os novos campos de endereço e telefone.
     */
    protected $fillable = [
        'nomeUnidade',
        'tipoUnidade',
        'telefoneUnidade',
        'logradouroUnidade',
        'numLogradouroUnidade',
        'bairroUnidade',
        'cepUnidade',
        'cidadeUnidade',
        'ufUnidade',
        'estadoUnidade',
        'paisUnidade',
    ];

    /**
     * Define a relação Muitos-para-Muitos com Médicos.
     */
    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'tbMedicoUnidade', 'idUnidadeFK', 'idMedicoFK');
    }

    /**
     * Define a relação Muitos-para-Muitos com Enfermeiros.
     */
    public function enfermeiros()
    {
        return $this->belongsToMany(Enfermeiro::class, 'tbEnfermeiroUnidade', 'idUnidadeFK', 'idEnfermeiroFK');
    }
}

