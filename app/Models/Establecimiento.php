<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Establecimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'codigo',
        'descripcion',
        'direccion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function puntosExpedicion()
    {
        return $this->hasMany(PuntoExpedicion::class);
    }

    public function numeraciones()
    {
        return $this->hasMany(Numeracion::class);
    }
}
