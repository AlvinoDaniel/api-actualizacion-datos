<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class DocumentoExterno extends Model
{
    use HasFactory;
    protected $table = 'documentos_externos';

    protected $fillable=[
        'numero_oficio',
        'id_remitente',
        'contenido',
        'estatus',
        'fecha_oficio',
        'departamento_receptor',
        'documento_respuesta',
        'responder',
        'fecha_entrada',
    ];

    const ESTATUS_RECIBIDO = "En Proceso";
    const ESTATUS_TRAMITADO = "Tramitado";
    const SIGLAS_EXTERNO = "ESN";

    public $timestamps = false;

    public function remitente() {
        return $this->belongsTo(RemitenteExterno::class);
    }

    public function dptoReceptor() {
        return $this->belongsTo(Departamento::class, 'departamento_receptor');
    }

    public function respuesta() {
        return $this->belongsTo(Documento::class, 'documento_respuesta');
    }

    public function asignado(): MorphOne
    {
        return $this->morphOne(DocumentoAsignado::class, 'documento');
    }

    static function generarCorrelativo(){
        return '';
    }
}
