<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timbrado extends Model
{
    use HasFactory;

    protected $table = 'timbrados';

    protected $fillable = [
        'empresa_id',
        'numero',
        'fecha_inicio',
        'fecha_fin',
        'ultimo_numero',
        'establecimiento',
        'punto_expedicion',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
