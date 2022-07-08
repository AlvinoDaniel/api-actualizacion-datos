<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarpertasOficio extends Model
{
    use HasFactory;

    protected $fillable=[
        'carpeta_id',
        'departamento_id',
    ];

}
