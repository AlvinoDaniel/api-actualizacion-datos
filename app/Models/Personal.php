<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;
use App\Models\User;
use App\Models\Nivel;
use Illuminate\Support\Facades\Storage;


class Personal extends Model
{
    use HasFactory;

    const NAME = 'Personal';

    protected $table = 'personal';

    protected $fillable=[
        'nombres_apellidos',
        'cedula_identidad',
        'tipo_personal',
        'codigo_unidad_admin',
        'codigo_unidad_ejec',
        'cargo_opsu',
        'cod_nucleo',
        'jefe',
        'cargo_jefe',
        'correo',
        'telefono',
        'pantalon',
        'camisa',
        'zapato',
    ];

    protected $casts = [
        'jefe' => 'boolean',
    ];

    protected $appends = ['has_update'];

    public function usuario()
    {
        return $this->hasOne(User::class);
    }

    public function getHasUpdateAttribute() {
        // return $this['cedula_identidad'];
        $fields = collect($this->fillable);

        return $fields->every(function ($value, $key) {
            return $this[$value] !== null;
        });
    }


}
