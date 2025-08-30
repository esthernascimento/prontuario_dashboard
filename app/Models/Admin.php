<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; 


class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; 

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
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Define os nomes das colunas de timestamp personalizadas.
     */
    const CREATED_AT = 'dataCadastroAdmin';
    const UPDATED_AT = null; // Mantido como null conforme a sua versão
    const DELETED_AT = 'deleted_at'; // 3. DEFINE A COLUNA DE SOFTDELETE

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
     */
    public function getAuthPassword()
    {
        return $this->senhaAdmin;
    }
}

