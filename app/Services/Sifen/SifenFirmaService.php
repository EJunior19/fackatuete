<?php

namespace App\Services\Sifen;

class SifenFirmaService
{
    private string $certPath;
    private string $password;

    private $certResource = null;   // Certificado X.509
    private $privateKey   = null;   // Llave privada RSA

    public function __construct(string $certPath, string $password)
    {
        $this->certPath = $certPath;
        $this->password = $password;

        $this->loadP12();
    }

    /**
     * Carga el archivo P12 y extrae llave privada y certificado
     */
    private function loadP12(): void
    {
        if (!file_exists($this->certPath)) {
            throw new \Exception("El archivo P12 no existe: {$this->certPath}");
        }

        $p12Content = file_get_contents($this->certPath);

        $certs = [];
        if (!openssl_pkcs12_read($p12Content, $certs, $this->password)) {
            throw new \Exception("No se pudo leer el archivo P12. Verifique la contraseña.");
        }

        // Certificado público
        $this->certResource = openssl_x509_read($certs['cert']);

        if (!$this->certResource) {
            throw new \Exception("No se pudo cargar el certificado del archivo P12.");
        }

        // Llave privada
        $this->privateKey = openssl_pkey_get_private($certs['pkey']);

        if (!$this->privateKey) {
            throw new \Exception("No se pudo cargar la clave privada del P12.");
        }
    }

    /**
     * Firma el XML (por ahora firma simulada)
     */
    public function firmarXml(string $xml): string
    {
        if (!$this->certResource || !$this->privateKey) {
            throw new \Exception("El certificado o la clave privada no fueron cargados.");
        }

        // Simulación (luego añadimos firma real)
        $firmado = "<XmlFirmado>\n" .
                   "  <CertificadoUsado>" . basename($this->certPath) . "</CertificadoUsado>\n" .
                   "  <ContenidoOriginal><![CDATA[$xml]]></ContenidoOriginal>\n" .
                   "</XmlFirmado>";

        return $firmado;
    }
}
