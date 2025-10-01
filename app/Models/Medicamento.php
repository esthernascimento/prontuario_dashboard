<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Medicamento extends Model
{
    protected $table = 'tbMedicamento';
    protected $primaryKey = 'idMedicamentoPK';
    protected $fillable = ['idConsultaFK', 'descMedicamento'];
}
