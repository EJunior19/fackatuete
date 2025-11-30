<?php

namespace App\Services\XML;

use App\Models\Documento;

class DEBuilder
{
    /**
     * Construye un XML mínimo de prueba basado en el Documento.
     * Luego lo iremos reemplazando con el XML real del MT 150.
     */
    public function buildFromDocumento(Documento $documento): string
    {
        $empresa = $documento->empresa;

        $xml = new \SimpleXMLElement('<rDE/>');
        $xml->addAttribute('xmlns', 'http://ekuatia.set.gov.py/sifen/xsd');

        $xml->addChild('dCDC', $documento->cdc ?? '');
        $xml->addChild('dRucEm', $empresa->ruc);
        $xml->addChild('dRazSocEm', htmlspecialchars($empresa->razon_social));
        $xml->addChild('dTotGralOpe', number_format($documento->total, 2, '.', ''));

        // Esto es solo la estructura mínima, después lo expandimos con TODOS los nodos.
        return $xml->asXML();
    }
}
