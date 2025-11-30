<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Numeracion extends Model
{
    use HasFactory;

    // 👇 ESTA LÍNEA ES LA QUE FALTABA
    protected $table = 'numeraciones';

    protected $fillable = [
        'empresa_id',
        'timbrado_id',
        'establecimiento_id',
        'punto_expedicion_id',
        'tipo_documento',
        'numero_inicial',
        'numero_actual',
        'numero_final',
        'activo',
    ];

    protected $casts = [
        'activo'          => 'boolean',
        'numero_inicial'  => 'integer',
        'numero_actual'   => 'integer',
        'numero_final'    => 'integer',
    ];
    public function generarNumero()
    {
        // Formato de 3-3-7 -> 001-001-0000001
        $establecimiento = str_pad($this->establecimiento_id ?? 1, 3, '0', STR_PAD_LEFT);
        $punto = str_pad($this->punto_expedicion_id ?? 1, 3, '0', STR_PAD_LEFT);
        $numero = str_pad($this->numero_actual, 7, '0', STR_PAD_LEFT);

        // Armar número final
        $numeroFactura = "{$establecimiento}-{$punto}-{$numero}";

        // Incrementar y guardar
        $this->numero_actual++;
        $this->save();

        return $numeroFactura;
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function timbrado()
    {
        return $this->belongsTo(Timbrado::class);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function puntoExpedicion()
    {
        return $this->belongsTo(PuntoExpedicion::class);
    }
}
