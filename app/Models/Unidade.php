<?php

namespace App\Models; // <-- Namespace do Model

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Enfermeiro;


class Unidade extends Authenticatable // <-- Herda de Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbUnidade';
    protected $primaryKey = 'idUnidadePK';

    
    protected $fillable = [
        'nomeUnidade',
        'tipoUnidade',
        'senhaUnidade',
        'telefoneUnidade',
        'cnpjUnidade',
        'emailUnidade',
        'statusAtivoUnidade',
        'logradouroUnidade',
        'numLogradouroUnidade',
        'bairroUnidade',
        'cepUnidade',
        'cidadeUnidade',
        'ufUnidade',
        'estadoUnidade',
        'paisUnidade',
    ];

    protected $hidden = [
        'senhaUnidade',
        'remember_token',
    ];

    /**
     * Define o campo de senha usado pelo Auth.
     */
    public function getAuthPassword()
    {
        return $this->senhaUnidade;
    }

    /**
     * Define o campo usado para login — neste caso, o CNPJ.
     */
    public function getAuthIdentifierName()
    {
        return 'cnpjUnidade';
    }

    /**
     * Relações com médicos e enfermeiros.
     */
    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'tbMedicoUnidade', 'idUnidadeFK', 'idMedicoFK');
    }


    public function enfermeiros()
    {
        return $this->belongsToMany(Enfermeiro::class, 'tbEnfermeiroUnidade', 'idUnidadeFK', 'idEnfermeiroFK');
    }
}