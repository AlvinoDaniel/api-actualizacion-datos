<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Oficio;
use App\Models\Personal;
use App\Models\Carpeta;
use App\Models\Plantilla;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable=[
        'nombre',
        'abreviacion',
    ];

    public function oficios() {
        return $this->belongsTo(Oficio::class);
    }
    
    public function carpetas() {
        return $this->belongsTo(Carpeta::class);
    }

    public function plantillas() {
        return $this->belongsTo(Plantilla::class);
    }
    
    public function recibidos()
    {
        return $this->belongsToMany(Oficio::class, 'oficios_deparatamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps();
    }
    
    public function personal() {
        return $this->belongsTo(Personal::class);
    }

}
