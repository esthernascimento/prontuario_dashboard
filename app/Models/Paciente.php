<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * O nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'tbPaciente';

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'cpf',
        'data_nasc',
        'cartao_sus',
        'nacionalidade',
        'genero',
        'caminho_foto',
        'telefone', // Campo de telefone adicionado
        'logradouro',
        'numero',
        'cep',
        'bairro',
        'cidade',
        'uf',
        'estado',
        'pais',
        'email',
        'senha',
    ];

    /**
     * Os atributos que devem ser ocultados para serialização.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'senha',
    ];

    /**
     * Diz ao Laravel qual é a coluna da senha para autenticação.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }
}

