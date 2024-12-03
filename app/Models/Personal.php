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
        'cargo_opsu',
        'cod_nucleo',
        'jefe',
        'cargo_jefe',
        'correo',
        'telefono',
        'pantalon',
        'camisa',
        'zapato',
        'area_trabajo',
        'tipo_calzado',
        'prenda_extra',
        'sexo',
    ];

    protected $casts = [
        'jefe' => 'boolean',
    ];

    protected $with = ['unidades'];

    protected $appends = ['has_update'];

    public function usuario()
    {
        return $this->hasOne(User::class);
    }

    public function unidades()
    {
        return $this->hasMany(PersonalUnidad::class, 'cedula_identidad', 'cedula_identidad');
    }

    public function nucleo()
    {
        return $this->hasOne(Nucleo::class, 'codigo_concatenado', 'cod_nucleo');
    }
    public function tipoPersonal()
    {
        return $this->hasOne(TipoPersonal::class, 'id', 'tipo_personal');
    }

    public function getHasUpdateAttribute() {

        $fields = collect($this->fillable);
        $excludeFlieds = ["jefe", "cargo_jefe", "cargo_opsu"];
        return $fields->every(function ($value, $key) use($excludeFlieds) {
            if(in_array($value, $excludeFlieds)) return true;
            return $this[$value] !== null;
        });
    }


}
