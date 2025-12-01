<?php

namespace App\Services\Firma;

use DOMDocument;

class XmlSigner
{
    /**
     * Firma lógica del XML (modo mock).
     *
     * @param string $xml             XML generado por XMLBuilder
     * @param string $privateKeyPem   Clave privada en PEM (no la usamos todavía)
     * @param string $certificatePem  Certificado en PEM (no lo usamos todavía)
     * @return string                 XML “firmado” (por ahora igual al original)
     */
    public static function sign(string $xml, string $privateKeyPem, string $certificatePem): string
    {
        // 👉 Por ahora NO tocamos la clave privada ni llamamos a OpenSSL acá.
        // Solo validamos que el XML sea bien formado y lo devolvemos.

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        if (!$dom->loadXML($xml)) {
            throw new \RuntimeException('XML inválido, no se pudo cargar en DOMDocument.');
        }

        // TODO: más adelante acá implementamos la firma XML real (XMLDSig + openssl_sign).
        return $dom->saveXML();
    }
}
