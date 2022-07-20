<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documento;
use App\Models\Personal;
use App\Models\Carpeta;
use App\Models\Plantilla;
use App\Models\Grupo;

class Departamento extends Model
{
    use HasFactory;

    const NAME = 'Departamento';
    
    protected $fillable=[
        'nombre',
        'siglas',
        'codigo',
        'correo',
    ];

    public function Documentos() {
        return $this->belongsTo(Documento::class);
    }
    
    public function carpetas() {
        return $this->belongsTo(Carpeta::class);
    }

    public function plantillas() {
        return $this->belongsTo(Plantilla::class);
    }
    
    public function recibidos()
    {
        return $this->belongsToMany(Documento::class, 'documentos_deparatamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps();
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupos_deparatamentos');
    }
    
    public function personal() {
        return $this->belongsTo(Personal::class);
    }

}
