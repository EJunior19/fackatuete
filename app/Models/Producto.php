<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'descripcion',
        'categoria',
        'unidad_medida',
        'precio_1',
        'precio_2',
        'precio_3',
        'iva',
        'activo',
    ];

    protected $casts = [
        'precio_1' => 'decimal:2',
        'precio_2' => 'decimal:2',
        'precio_3' => 'decimal:2',
        'activo'   => 'boolean',
    ];

    // Filtro general
    public function scopeBuscar($query, $term)
    {
        if (!$term) return $query;

        return $query->where('descripcion', 'ILIKE', "%$term%")
                     ->orWhere('codigo', 'ILIKE', "%$term%");
    }

    public function items()
    {
        return $this->hasMany(\App\Models\Item::class, 'producto_id');
    }

}
