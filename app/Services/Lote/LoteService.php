<?php

namespace App\Services\Lote;

use App\Models\Lote;

class LoteService
{
    /**
     * Construye el XML del lote con uno o más DEs firmados.
     */
    public function buildLotePayload(Lote $lote): string
    {
        $empresa = $lote->empresa;

        // Recuperar documentos asociados
        $documentos = $lote->documentos;

        // Si no hay DEs, SIFEN igual responde 0300, pero queda vacío
        $deXml = '';

        foreach ($documentos as $doc) {
            $xml = $doc->xml_firmado;

            // Quitamos encabezado XML
            $xml = preg_replace('/<\?xml.*?\?>/', '', $xml);

            // Eliminamos saltos al inicio
            $xml = trim($xml);

            // Agregamos dentro del tag <DE>
            $deXml .= "<DE>\n{$xml}\n</DE>\n";
        }

        // Número de lote
        $idLote = $lote->numero_lote;

        // Construcción del envelope SIFEN
        return <<<XML
<rEnvLoteDe xmlns="http://ekuatia.set.gov.py/sifen/xsd">
    <dId>{$idLote}</dId>
    <dProtAut>1</dProtAut>
    {$deXml}
</rEnvLoteDe>
XML;
    }
}
