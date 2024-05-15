<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentoAsignado extends Model
{
    use HasFactory;

    protected $table = 'documentos_asignados';

    protected $fillable=[
        'documento_id',
        'documento_type',
        'departamento_id',
        'fecha_leido',
        'fecha_asignado',
        'leido',
    ];

    public $timestamps = false;

    public function asignado() {
        return $this->belongsTo(Departamento::class);
    }

    public function documento(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'documento_type', 'documento_id');
    }

}
