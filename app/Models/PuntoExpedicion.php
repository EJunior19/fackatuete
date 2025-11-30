<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PuntoExpedicion extends Model
{
    use HasFactory;

    protected $table = 'puntos_expedicion';

    protected $fillable = [
        'empresa_id',
        'establecimiento_id',
        'codigo',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function numeraciones()
    {
        return $this->hasMany(Numeracion::class);
    }
}
