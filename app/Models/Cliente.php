<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 👈 IMPORTANTE

class Cliente extends Model
{
    use SoftDeletes; // 👈 ACTIVAR SOFT DELETE

    protected $fillable = [
        'ruc',
        'dv',
        'razon_social',
        'telefono',
        'email',
        'direccion',
    ];
}
