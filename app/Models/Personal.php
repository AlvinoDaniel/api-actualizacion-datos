<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;
use App\Models\User;

class Personal extends Model
{
    use HasFactory;

    const NAME = 'Personal';

    protected $table = 'personal';

    protected $fillable=[
        'nombres_apellidos',
        'cedula_identidad',
        'cargo',
        'correo',
        'jefe',
        'descripcion_cargo',
        'cod_nucleo',
        'firma',
        'departamento_id',
    ];

    public function usuario()
    {
        return $this->hasOne(User::class);
    }

    public function departamento() {
        return $this->belongsTo(Departamento::class);
    }


}
