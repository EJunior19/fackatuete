<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'documento_id',
        'producto_id',
        'descripcion',
        'codigo',
        'cantidad',
        'precio_unit',   // precio unitario SIN IVA
        'total',         // total CON IVA
        'iva',           // 10 / 5 / 0
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
