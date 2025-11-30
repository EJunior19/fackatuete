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
    ];

    protected $casts = [
        'fecha_emision'   => 'datetime',
        'fecha_envio'     => 'datetime',
        'fecha_respuesta' => 'datetime',
    ];

    // 🚀 Evento automático para asignar numeración
    protected static function boot()
    {
        parent::boot();

        static::created(function (Documento $doc) {
            $doc->asignarNumeracion();
        });
    }

    // 🔥 Generar numeración usando Numeracion::generarNumero()
    public function asignarNumeracion()
    {
        $num = Numeracion::where('empresa_id', $this->empresa_id)
            ->where('timbrado_id', $this->timbrado_id)
            ->where('tipo_documento', $this->tipo_documento)
            ->where('activo', true)
            ->first();

        if (!$num) {
            throw new \Exception("No existe numeración activa para este timbrado.");
        }

        // Debe devolver algo tipo 001-001-0000001 y avanzar numero_actual
        $numeroCompleto = $num->generarNumero();

        [$establecimiento, $punto, $numero] = explode('-', $numeroCompleto);

        $this->establecimiento  = $establecimiento;
        $this->punto_expedicion = $punto;
        $this->numero           = $numero;
        $this->save();
    }

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
        return $this->belongsTo(Lote::class, 'numero_lote', 'numero_lote');
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
