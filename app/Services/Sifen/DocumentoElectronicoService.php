<?php

namespace App\Services\Sifen;

use App\Models\Documento;

class DocumentoElectronicoService
{
    public function procesar(Documento $documento): array
    {
        // 1) Generar XML segun SIFEN
        $xml = XMLBuilder::generar($documento);

        // 2) Firmar XML con el cert de la empresa
        $xmlFirmado = XmlSigner::firmarXML($xml, $documento->empresa);

        // 3) Guardar en disco
        $dir = storage_path('app/xml');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $path = $dir . "/documento_{$documento->id}.xml";
        file_put_contents($path, $xmlFirmado);

        return [
            'xml'  => $xmlFirmado,
            'path' => $path,
        ];
    }
}
