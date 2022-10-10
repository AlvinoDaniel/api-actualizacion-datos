<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documento;
use App\Models\Personal;
use App\Models\Carpeta;
use App\Models\Plantilla;
use App\Models\Grupo;
use App\Models\User;

class Departamento extends Model
{
    use HasFactory;

    const NAME = 'Departamento';

    protected $fillable=[
        'nombre',
        'siglas',
        'codigo',
        'correo',
        'configuracion',
    ];

    public function documentos() {
        return $this->hasMany(Documento::class, 'departamento_id');
    }

    public function carpetas() {
        return $this->belongsTo(Carpeta::class);
    }

    public function plantillas() {
        return $this->belongsTo(Plantilla::class);
    }

    public function recibidos()
    {
        return $this->belongsToMany(Documento::class, 'documentos_departamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps();
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupos_departamentos');
    }

    public function personal() {
        return $this->belongsTo(Personal::class);
    }

    public function GET_JEFE() {
        $jefe = User::whereHas('personal' , function (Builder $query) use($personal) {
            $query->where('departamento_id', $this->id);
          })
          ->whereHas('roles', function (Builder $query) {
            $query->where('name', 'jefe');
          })
          ->first();

        return $jefe;
    }

}
