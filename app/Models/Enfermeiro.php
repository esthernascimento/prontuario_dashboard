<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermeiro extends Model
{
    use HasFactory;

    protected $table = 'tbEnfermeiro'; 
    protected $primaryKey = 'idEnfermeiroPK';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario', 
        'nomeEnfermeiro',
        'emailEnfermeiro',
        'corenEnfermeiro',
        'especialidadeEnfermeiro',
        'genero',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'idUsuarioPK'); // ✅ CORRIGIDO: chave estrangeira
    }
}