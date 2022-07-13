<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;

class Personal extends Model
{
    use HasFactory;

    const NAME = 'Personal';

    protected $fillable=[
        'nombres',
        'apellidos',
        'cedula_identidad',
        'cargo',
        'correo',
        'firma',
        'departamento_id',
    ];

    public function departamento()
    {
        return $this->hasOne(Departamento::class);
    }


}
