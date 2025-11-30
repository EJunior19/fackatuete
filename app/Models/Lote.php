<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';

    protected $fillable = [
        'empresa_id',
        'numero_lote',
        'cantidad',
        'estado',
        'respuesta',
        'fecha_envio',
        'fecha_respuesta',
        'xml_enviado',
        'xml_respuesta',
    ];

    protected $casts = [
        'fecha_envio'     => 'datetime',
        'fecha_respuesta' => 'datetime',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'numero_lote', 'numero_lote');
    }
}
