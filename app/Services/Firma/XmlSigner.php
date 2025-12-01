<?php

namespace App\Services\Firma;

use DOMDocument;
use RuntimeException;
use Illuminate\Support\Facades\Log;

class XmlSigner
{
    /**
     * Firma un XML usando una clave privada (PEM) y un certificado X.509 (PEM),
     * tomando como entrada las RUTAS relativas/absolutas de ambos archivos.
     *
     * @param  string      $xml              XML original
     * @param  string      $certPublicPath   Ruta del certificado público (ej: "certs/empresa_1/cert.crt")
     * @param  string      $certPrivatePath  Ruta de la clave privada (ej: "certs/empresa_1/cert.key")
     * @param  string|null $passphrase       Password de la clave privada (si está encriptada)
     * @return string                        XML firmado (firma simple de prueba)
     */
    public static function sign(
        string $xml,
        string $certPublicPath,
        string $certPrivatePath,
        ?string $passphrase = null
    ): string {
        // Normalizar rutas: si no empiezan con '/', asumimos que son relativas a storage/app
        if (! str_starts_with($certPublicPath, '/')) {
            $certPublicPath = storage_path('app/' . $certPublicPath);
        }

        if (! str_starts_with($certPrivatePath, '/')) {
            $certPrivatePath = storage_path('app/' . $certPrivatePath);
        }

        if (! file_exists($certPrivatePath)) {
            throw new RuntimeException("Archivo de clave privada no encontrado: {$certPrivatePath}");
        }

        if (! file_exists($certPublicPath)) {
            throw new RuntimeException("Archivo de certificado no encontrado: {$certPublicPath}");
        }

        // Leer contenidos PEM
        $privateKeyPem = file_get_contents($certPrivatePath);
        $certPem       = file_get_contents($certPublicPath);

        // Intentar obtener el recurso de clave privada usando el password
        $password = $passphrase ?? '';

        $pkey = openssl_pkey_get_private($privateKeyPem, $password);

        if (! $pkey) {
            // Logueamos todos los errores de OpenSSL para ver qué pasa
            $errors = [];
            while ($msg = openssl_error_string()) {
                $errors[] = $msg;
            }

            Log::error('XmlSigner: no se pudo cargar la clave privada.', [
                'private_key_path' => $certPrivatePath,
                'openssl_errors'   => $errors,
            ]);

            throw new RuntimeException('No se pudo cargar la clave privada. Verifica password / formato PEM.');
        }

        // Cargamos el XML en DOM
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;

        if (! $doc->loadXML($xml)) {
            throw new RuntimeException('No se pudo cargar el XML para firmar.');
        }

        // Firma simple de prueba: firmamos el XML canonicalizado
        $dataToSign = $doc->C14N(); // Canonicalización básica

        $signature = null;
        $ok = openssl_sign($dataToSign, $signature, $pkey, OPENSSL_ALGO_SHA256);

        // Liberamos el recurso de clave
        openssl_pkey_free($pkey);

        if (! $ok || ! $signature) {
            $errors = [];
            while ($msg = openssl_error_string()) {
                $errors[] = $msg;
            }

            Log::error('XmlSigner: error al firmar el XML.', [
                'openssl_errors' => $errors,
            ]);

            throw new RuntimeException('No se pudo firmar el XML con la clave privada.');
        }

        // Creamos el nodo Signature (firma de prueba)
        $signatureNode = $doc->createElement('Signature', base64_encode($signature));

        // También agregamos el certificado en base64 (por ahora todo el PEM)
        $certNode = $doc->createElement('X509Certificate', base64_encode($certPem));
        $signatureNode->appendChild($certNode);

        // Lo pegamos al nodo raíz
        $doc->documentElement->appendChild($signatureNode);

        // Devolvemos el XML firmado
        return $doc->saveXML();
    }
}
