<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 👉 Importante si vas a usar API Tokens / Integración externa
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos rellenables (mass assignable)
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        // 👉 Si después querés agregar:
        // 'role',
        // 'empresa_id',
        // 'is_active',
    ];

    /**
     * Campos ocultos al serializar
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts automáticos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ============================================
     *  RELACIONES (si querés activar más adelante)
     * ============================================ */

    // public function empresa()
    // {
    //     return $this->belongsTo(Empresa::class);
    // }

    // public function documentos()
    // {
    //     return $this->hasMany(Documento::class);
    // }
}
