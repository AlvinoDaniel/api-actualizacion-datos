<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;
use App\Models\OficiosDepartamento;

class Oficio extends Model
{
    use HasFactory;

    protected $fillable=[
        'asunto',
        'nro_oficio',
        'contenido',
        'tipo_oficio',
        'estatus',
        'fecha_enviado',
        'departamento_id',
    ];

    public function propietario()
    {
        return $this->hasOne(Departamento::class);
    }

    public function destino()
    {
        return $this->hasOne(OficiosDepartamento::class)->where('copia', false);
    }
    
    public function enviados()
    {
        return $this->belongsToMany(Departamento::class, 'oficios_deparatamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps()->wherePivot('copia', false);
    }

    public function copias()
    {
        return $this->belongsToMany(Departamento::class, 'oficios_deparatamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps()->wherePivot('copia', true);
    }
          
}
