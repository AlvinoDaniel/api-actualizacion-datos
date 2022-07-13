<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;
use App\Models\DocumentosDepartamento;

class Documento extends Model
{
    use HasFactory;

    const NAME = 'Documento';

    protected $fillable=[
        'asunto',
        'nro_documento',
        'contenido',
        'tipo_documento',
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
        return $this->hasOne(documentosDepartamento::class)->where('copia', false);
    }
    
    public function enviados()
    {
        return $this->belongsToMany(Departamento::class, 'documentos_deparatamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps()->wherePivot('copia', false);
    }

    public function copias()
    {
        return $this->belongsToMany(Departamento::class, 'documentos_deparatamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps()->wherePivot('copia', true);
    }
          
}
