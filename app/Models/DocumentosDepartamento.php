<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DocumentosDepartamento extends Pivot
{
    use HasFactory;

    protected $fillable=[
        'documento_id',
        'departamento_id',
        'leido',
        'copia',
        'fecha_leido',
    ];
    
}
