<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruposDepartamento extends Model
{
    use HasFactory;

    protected $fillable=[
        'grupo_id',
        'departamento_id',
    ];
}
