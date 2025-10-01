<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Exame extends Model
{
    protected $table = 'tbExame';
    protected $primaryKey = 'idExamePK';
    protected $fillable = ['idConsultaFK', 'descExame'];
}

