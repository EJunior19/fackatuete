<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'ruc',
        'razon_social',
        'nombre_fantasia',
        'dv',
        'direccion',
        'telefono',
        'email',
        'cert_publico',
        'cert_privado',
        'cert_password',
        'ambiente',
        'activo',
    ];

    public function cargarCertificadoP12($rutaP12, $password)
{
    if (!file_exists($rutaP12)) {
        throw new \Exception("Archivo P12 no encontrado.");
    }

    $contenido = file_get_contents($rutaP12);

    $certs = [];
    if (!openssl_pkcs12_read($contenido, $certs, $password)) {
        throw new \Exception("No se pudo leer el archivo P12. Verifique la contraseña.");
    }

    // Guardar en DB
    $this->cert_publico  = $certs['cert'];
    $this->cert_privado  = $certs['pkey'];
    $this->cert_password = $password;
    $this->cert_p12_path = $rutaP12;

    $this->save();

    return true;
}


// --------------------------------------------------------
// 🔐 FIRMA XML --- esto lo usa el SIFEN
// --------------------------------------------------------
public function firmarXML($xml)
{
    if (!$this->cert_privado || !$this->cert_publico) {
        throw new \Exception("La empresa no tiene certificados cargados.");
    }

    $pkey = openssl_pkey_get_private($this->cert_privado, $this->cert_password);
    if (!$pkey) {
        throw new \Exception("No se pudo obtener la clave privada.");
    }

    // Firmar contenido
    openssl_sign($xml, $firma, $pkey, OPENSSL_ALGO_SHA256);

    return base64_encode($firma);
}


    // Relaciones
    public function timbrados()
    {
        return $this->hasMany(Timbrado::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }
    public function establecimientos()
    {
        return $this->hasMany(Establecimiento::class);
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
