<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'documento_id',
        'lote_id',
        'codigo',
        'tipo',
        'descripcion',
        'mensaje',
        'xml',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }
}
