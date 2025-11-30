<?php

namespace App\Services\XML;

use DOMDocument;

class XmlGenerator
{
    /**
     * Generar XML principal del Documento Electrónico.
     */
    public static function generarFactura(array $data): string
    {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->standalone = true;
        $xml->formatOutput = true;

        // Nodo raíz
        $DE = $xml->createElementNS('http://ekuatia.set.gov.py/sifen/xsd', 'rDE');
        $DE = $xml->appendChild($DE);

        // ---------------------------------------------------------------------
        // 1) Identificación del Documento Electrónico (DE)
        // ---------------------------------------------------------------------
        $DE->appendChild(self::generaIdDE($xml, $data));

        // ---------------------------------------------------------------------
        // 2) Datos del Emisor
        // ---------------------------------------------------------------------
        $DE->appendChild(self::generaEmisor($xml, $data));

        // ---------------------------------------------------------------------
        // 3) Datos del Receptor
        // ---------------------------------------------------------------------
        $DE->appendChild(self::generaReceptor($xml, $data));

        // ---------------------------------------------------------------------
        // 4) Items
        // ---------------------------------------------------------------------
        $DE->appendChild(self::generaItems($xml, $data['items']));

        // ---------------------------------------------------------------------
        // 5) Totales
        // ---------------------------------------------------------------------
        $DE->appendChild(self::generaTotales($xml, $data));

        // XML final listo para firmar
        return $xml->saveXML();
    }

    // =========================================================================
    //  SECCIONES DEL XML
    // =========================================================================

    private static function generaIdDE(DOMDocument $xml, array $data)
    {
        $id = $xml->createElement('DE_Id');

        $id->appendChild($xml->createElement('dDVId', $data['dv']));
        $id->appendChild($xml->createElement('dFecEmiDE', $data['fecha_emision']));
        $id->appendChild($xml->createElement('dNumTim', $data['timbrado']));
        $id->appendChild($xml->createElement('dEst', $data['establecimiento']));
        $id->appendChild($xml->createElement('dPunExp', $data['punto']));
        $id->appendChild($xml->createElement('dNumDoc', $data['numero']));
        $id->appendChild($xml->createElement('Id', $data['cdc']));

        return $id;
    }

    private static function generaEmisor(DOMDocument $xml, array $data)
    {
        $emi = $xml->createElement('DE_Emi');

        $emi->appendChild($xml->createElement('dRucEm', $data['ruc']));
        $emi->appendChild($xml->createElement('dDVEmi', $data['dv_ruc']));
        $emi->appendChild($xml->createElement('dNomEmi', $data['razon_social']));
        $emi->appendChild($xml->createElement('dDirEmi', $data['direccion']));
        $emi->appendChild($xml->createElement('dTelEmi', $data['telefono']));

        return $emi;
    }

    private static function generaReceptor(DOMDocument $xml, array $data)
    {
        $rec = $xml->createElement('DE_Rec');

        $rec->appendChild($xml->createElement('dRucRec', $data['cliente_ruc']));
        $rec->appendChild($xml->createElement('dDVRec', $data['cliente_dv']));
        $rec->appendChild($xml->createElement('dNomRec', $data['cliente_nombre']));

        return $rec;
    }

    private static function generaItems(DOMDocument $xml, array $items)
    {
        $gItems = $xml->createElement('gDItems');

        foreach ($items as $i) {
            $item = $xml->createElement('gItem');

            $item->appendChild($xml->createElement('dDesProSer', $i['descripcion']));
            $item->appendChild($xml->createElement('dCantProSer', $i['cantidad']));
            $item->appendChild($xml->createElement('dPUniProSer', $i['precio']));
            $item->appendChild($xml->createElement('dTasaIVA', $i['iva']));
            $item->appendChild($xml->createElement('dSubTotIVA', $i['subtotal']));

            $gItems->appendChild($item);
        }

        return $gItems;
    }

    private static function generaTotales(DOMDocument $xml, array $data)
    {
        $tot = $xml->createElement('DE_Tot');

        $tot->appendChild($xml->createElement('dTotOpGrav', $data['total_gravado']));
        $tot->appendChild($xml->createElement('dTotIVA', $data['total_iva']));
        $tot->appendChild($xml->createElement('dTotCom', $data['total']));

        return $tot;
    }
}
