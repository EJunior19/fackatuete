<?php
// app/Services/Empresa/EmpresaCertificateService.php
namespace App\Services\Empresa;

use App\Models\Empresa;

class EmpresaCertificateService
{
    public function getCertAndKey(int $empresaId = 1): array
    {
        $empresa = Empresa::findOrFail($empresaId);

        $path   = storage_path($empresa->cert_p12_path); // ej: storage/app/certs/empresa_1/cert.p12
        $pkcs12 = file_get_contents($path);

        $certs = [];
        $ok    = openssl_pkcs12_read($pkcs12, $certs, $empresa->cert_password);

        if (! $ok) {
            throw new \RuntimeException('No se pudo leer el archivo .p12 de la empresa.');
        }

        // $certs suele traer:
        //   - $certs['cert']  => certificado X.509
        //   - $certs['pkey']  => clave privada
        return $certs;
    }
}
