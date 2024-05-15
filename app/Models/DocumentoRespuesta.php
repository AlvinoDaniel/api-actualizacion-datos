<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoRespuesta extends Model
{
    use HasFactory;
    protected $table = 'documentos_respuestas';

    protected $fillable=[
        'id_documento',
        'documento_respuesta',
        'fecha_respuesta',
    ];

    public $timestamps = false;

    public function respuesta() {
        return $this->belongsTo(Documento::class, 'documento_respuesta');
    }

    public function documento() {
        return $this->belongsTo(Documento::class, 'id_documento');
    }

}
