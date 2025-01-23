<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalUnidad extends Model
{
    use HasFactory;

    protected $table = 'personal_unidades';
    protected $fillable=[
        'cedula_identidad',
        'codigo_unidad_admin',
        'codigo_unidad_ejec',
    ];

    protected $with = ['entidad'];
    public function entidad()
    {
        return $this->hasOne(Unidad::class, 'codigo_unidad_admin', 'codigo_unidad_admin');
    }

}
