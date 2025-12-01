<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Numeracion;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'empresa_id',
        'timbrado_id',
        'cdc',
        'tipo_documento',
        'numero',
        'estado_sifen',
        'mensaje_sifen',
        'fecha_emision',

        // CLIENTE
        'cliente_id',
        'cliente_ruc',
        'cliente_dv',
        'cliente_nombre',

        // TOTALES
        'total_gravada_10',
        'total_gravada_5',
        'total_exenta',
        'total_iva',
        'total_general',

        // OTROS
        'xml_generado',
        'xml_firmado',
        'xml_respuesta',
        'numero_lote',
        'fecha_envio',
        'fecha_respuesta',
        'establecimiento',
        'punto_expedicion',

        // 👇 agregamos esto
        'lote_id',
    ];

    protected $casts = [
        'fecha_emision'   => 'datetime',
        'fecha_envio'     => 'datetime',
        'fecha_respuesta' => 'datetime',
    ];

    // boot() y asignarNumeracion() los dejás igual...

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function timbrado()
    {
        return $this->belongsTo(Timbrado::class);
    }

    public function lote()
    {
        // 🔥 RELACIÓN INVERSA TAMBIÉN POR lote_id
        return $this->belongsTo(Lote::class, 'lote_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
