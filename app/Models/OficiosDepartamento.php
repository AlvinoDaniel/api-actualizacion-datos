<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OficiosDepartamento extends Pivot
{
    use HasFactory;

    protected $fillable=[
        'oficio_id',
        'departamento_id',
        'leido',
        'copia',
        'fecha_leido',
    ];
    
}
