<?php

namespace App\Services\Firma;

use DOMDocument;
use RuntimeException;

class XmlSigner
{
    /**
     * Firma un XML con RSA-SHA256 (XMLDSig Enveloped) listo para SIFEN.
     *
     * @param string      $xml           XML a firmar
     * @param string      $certPublicPem Certificado público en PEM (X509)
     * @param string      $certPrivatePem Clave privada en PEM
     * @param string|null $certPassword  Password de la clave privada (si tiene)
     *
     * @return string XML firmado
     */
    public static function sign(string $xml, string $certPublicPem, string $certPrivatePem, ?string $certPassword = null): string
    {
        // 1) Cargar XML original
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = false;

        if (! $doc->loadXML($xml)) {
            throw new RuntimeException('No se pudo cargar el XML para firmar.');
        }

        $root = $doc->documentElement;

        // 2) Canonicalizar TODO el documento (sin Signature todavía) para el digest
        $canonical = $doc->C14N(true, false);
        if ($canonical === false) {
            throw new RuntimeException('No se pudo canonicalizar el XML.');
        }

        // 3) Calcular Digest SHA256 en base64
        $digestValue = base64_encode(hash('sha256', $canonical, true));

        // Namespace XMLDSig
        $ds = 'http://www.w3.org/2000/09/xmldsig#';

        // 4) Crear nodo <ds:Signature> (enveloped)
        $signature = $doc->createElementNS($ds, 'ds:Signature');
        $root->appendChild($signature);

        // ---------------------------
        // 4.1 SignedInfo
        // ---------------------------
        $signedInfo = $doc->createElementNS($ds, 'ds:SignedInfo');
        $signature->appendChild($signedInfo);

        // CanonicalizationMethod
        $canonicalizationMethod = $doc->createElementNS($ds, 'ds:CanonicalizationMethod');
        $canonicalizationMethod->setAttribute(
            'Algorithm',
            'http://www.w3.org/TR/2001/REC-xml-c14n-20010315'
        );
        $signedInfo->appendChild($canonicalizationMethod);

        // SignatureMethod
        $signatureMethod = $doc->createElementNS($ds, 'ds:SignatureMethod');
        $signatureMethod->setAttribute(
            'Algorithm',
            'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256'
        );
        $signedInfo->appendChild($signatureMethod);

        // Reference (a todo el documento)
        $reference = $doc->createElementNS($ds, 'ds:Reference');
        $reference->setAttribute('URI', '');
        $signedInfo->appendChild($reference);

        // Transforms
        $transforms = $doc->createElementNS($ds, 'ds:Transforms');
        $reference->appendChild($transforms);

        // Transform 1: Enveloped Signature
        $transform1 = $doc->createElementNS($ds, 'ds:Transform');
        $transform1->setAttribute(
            'Algorithm',
            'http://www.w3.org/2000/09/xmldsig#enveloped-signature'
        );
        $transforms->appendChild($transform1);

        // Transform 2: Canonicalización
        $transform2 = $doc->createElementNS($ds, 'ds:Transform');
        $transform2->setAttribute(
            'Algorithm',
            'http://www.w3.org/TR/2001/REC-xml-c14n-20010315'
        );
        $transforms->appendChild($transform2);

        // DigestMethod
        $digestMethod = $doc->createElementNS($ds, 'ds:DigestMethod');
        $digestMethod->setAttribute(
            'Algorithm',
            'http://www.w3.org/2001/04/xmlenc#sha256'
        );
        $reference->appendChild($digestMethod);

        // DigestValue
        $digestValueNode = $doc->createElementNS($ds, 'ds:DigestValue', $digestValue);
        $reference->appendChild($digestValueNode);

        // ---------------------------
        // 4.2 Calcular SignatureValue
        // ---------------------------
        // Canonicalizar SOLO SignedInfo
        $signedInfoC14N = $signedInfo->C14N(true, false);
        if ($signedInfoC14N === false) {
            throw new RuntimeException('No se pudo canonicalizar SignedInfo.');
        }

        // Cargar clave privada
        $privateKey = openssl_pkey_get_private($certPrivatePem, $certPassword ?? '');
        if ($privateKey === false) {
            throw new RuntimeException('No se pudo cargar la clave privada. Verifica password / formato PEM.');
        }

        // Firmar SignedInfo
        $binarySignature = '';
        $ok = openssl_sign($signedInfoC14N, $binarySignature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        if (! $ok) {
            throw new RuntimeException('Falló la operación openssl_sign.');
        }

        $signatureValue = base64_encode($binarySignature);

        // SignatureValue node
        $signatureValueNode = $doc->createElementNS($ds, 'ds:SignatureValue', $signatureValue);
        $signature->appendChild($signatureValueNode);

        // ---------------------------
        // 4.3 KeyInfo con X509Certificate
        // ---------------------------
        $certClean = self::cleanCertificate($certPublicPem);

        $keyInfo = $doc->createElementNS($ds, 'ds:KeyInfo');
        $signature->appendChild($keyInfo);

        $x509Data = $doc->createElementNS($ds, 'ds:X509Data');
        $keyInfo->appendChild($x509Data);

        $x509CertNode = $doc->createElementNS($ds, 'ds:X509Certificate', $certClean);
        $x509Data->appendChild($x509CertNode);

        // 5) Devolver XML firmado
        $doc->formatOutput = true;
        return $doc->saveXML();
    }

    /**
     * Limpia el certificado PEM para dejar solo el contenido base64 del X509.
     */
    private static function cleanCertificate(string $certPem): string
    {
        // Quitar encabezados, pies y saltos de línea
        $cert = str_replace(["\r", "\n"], '', $certPem);
        $cert = str_replace(
            [
                '-----BEGIN CERTIFICATE-----',
                '-----END CERTIFICATE-----',
                '-----BEGIN CERTIFICATE-----',
                '-----END CERTIFICATE-----',
                ' '
            ],
            '',
            $cert
        );

        return trim($cert);
    }
}
