<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * A tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'tbAdmin';

    /**
     * A chave primária da tabela.
     *
     * @var string
     */
    protected $primaryKey = 'idAdminPK';

    /**
     * Gerenciamento dos timestamps.
     * A tabela admin só tem data de cadastro, então desabilitamos a de atualização.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Define os nomes das colunas de timestamp personalizadas.
     */
    const CREATED_AT = 'dataCadastroAdmin';
    const UPDATED_AT = null;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomeAdmin',
        'emailAdmin',
        'senhaAdmin',
    ];

    /**
     * Os atributos que devem ser ocultados para serialização.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'senhaAdmin',
    ];

    /**
     * Sobrescreve o método para obter o nome da coluna da senha.
     * Necessário para o sistema de autenticação do Laravel.
     */
    public function getAuthPassword()
    {
        return $this->senhaAdmin;
    }
}
