<?php

namespace App\Services\Sifen;

use Illuminate\Support\Facades\Log;

class XMLBuilder
{
    /**
     * Genera el XML DE según especificación del SIFEN
     */
    public static function generar($documento)
    {
        $empresa = $documento->empresa;
        $cliente = $documento->cliente;
        $timbrado = $documento->timbrado;
        $items = $documento->items;

        // CDC
        $cdc = CDCGenerator::generar($documento);

        // Fecha ISO 8601
        $fechaEmision = $documento->fecha_emision->format('Y-m-d\TH:i:s');

        // ========================
        // Construcción del XML
        // ========================
        $xml = new \SimpleXMLElement('<rDE xmlns="http://ekuatia.set.gov.py/sifen/xsd"></rDE>');

        // -----------------------------------------
        // gCabDE → Datos principales del DE
        // -----------------------------------------
        $gCabDE = $xml->addChild("gCabDE");

        $gCabDE->addChild("IdCdc", $cdc);
        $gCabDE->addChild("dDVId", substr($cdc, -1));
        $gCabDE->addChild("dFecEmiDE", $fechaEmision);

        // Tipo DE (Factura Electrónica → 1)
        $gCabDE->addChild("dTiDE", 1);

        // -----------------------------------------
        // gOpeDE → Operación
        // -----------------------------------------
        $gOpeDE = $gCabDE->addChild("gOpeDE");
        $gOpeDE->addChild("iTipEmi", 1); // normal
        $gOpeDE->addChild("dDesdTipEmi", "Normal");

        // -----------------------------------------
        // gEmis → Emisor
        // -----------------------------------------
        $gEmis = $gCabDE->addChild("gEmis");
        $gEmis->addChild("dRucEm", $empresa->ruc);
        $gEmis->addChild("dDVEmi", $empresa->dv);
        $gEmis->addChild("dNomEmi", htmlspecialchars($empresa->razon_social));
        $gEmis->addChild("dDirEmi", htmlspecialchars($empresa->direccion ?? "SIN DIRECCIÓN"));

        // Establecimiento
        $gEmis->addChild("dEst", str_pad($documento->establecimiento, 3, '0', STR_PAD_LEFT));
        $gEmis->addChild("dPunExp", str_pad($documento->punto_expedicion, 3, '0', STR_PAD_LEFT));
        $gEmis->addChild("dNumTim", $timbrado->numero);
        $gEmis->addChild("dFeIniT", $timbrado->fecha_inicio);

        // -----------------------------------------
        // gDatRec → Receptor
        // -----------------------------------------
        $gDatRec = $gCabDE->addChild("gDatRec");
        $gDatRec->addChild("iNatRec", 1); // persona jurídica
        $gDatRec->addChild("iTiOpe", 1); // operaciones internas
        $gDatRec->addChild("dRucRec", $cliente->ruc);
        $gDatRec->addChild("dDVRec", $cliente->dv);
        $gDatRec->addChild("dNomRec", htmlspecialchars($cliente->razon_social));
        $gDatRec->addChild("dDirecRec", htmlspecialchars($cliente->direccion ?? "SIN DIRECCIÓN"));

        // -----------------------------------------
        // gDtipDE → Tipo de Documento (Factura)
        // -----------------------------------------
        $gDtipDE = $gCabDE->addChild("gDtipDE");
        $gDtipDE->addChild("iTipDoc", 1); // factura
        $gDtipDE->addChild("dDesTipDoc", "FACTURA ELECTRÓNICA");

        // -----------------------------------------
        // gCamFE → Campos de la Factura
        // -----------------------------------------
        $gCamFE = $gCabDE->addChild("gCamFE");
        $gCamFE->addChild("dDirPaiRec", "PY");
        $gCamFE->addChild("dCondTiCam", 1); // contado

        // -----------------------------------------
        // gCamItem[] → Items
        // -----------------------------------------
        foreach ($items as $item) {

            $gCamItem = $xml->addChild("gCamItem");

            $gCamItem->addChild("dCodInt", $item->codigo);
            $gCamItem->addChild("dDesProSer", htmlspecialchars($item->descripcion));
            $gCamItem->addChild("dCantProSer", number_format($item->cantidad, 2, '.', ''));
            $gCamItem->addChild("dPUniProSer", number_format($item->precio_unit, 0, '.', ''));
            $gCamItem->addChild("dTotBruOpeItem", number_format($item->total, 0, '.', ''));

            // IVA
            $gCamIVA = $gCamItem->addChild("gCamIVA");
            $gCamIVA->addChild("iAfecIVA", 1);
            $gCamIVA->addChild("dTasaIVA", $item->iva);
            $gCamIVA->addChild("dBasGravIVA", number_format($item->total, 0, '.', ''));
            $gCamIVA->addChild("dLiqIVAItem", number_format($item->total * 0.1, 0, '.', ''));
        }

        // -----------------------------------------
        // gTotSub → Totales
        // -----------------------------------------
        $gTotSub = $xml->addChild("gTotSub");
        $gTotSub->addChild("dSubExe", 0);
        $gTotSub->addChild("dSub5", 0);
        $gTotSub->addChild("dSub10", number_format($documento->total_gravado, 0, '.', ''));
        $gTotSub->addChild("dTotBru", number_format($documento->total, 0, '.', ''));
        $gTotSub->addChild("dTotIVA", number_format($documento->total_iva, 0, '.', ''));
        $gTotSub->addChild("dTotGralOpe", number_format($documento->total, 0, '.', ''));

        // Convertir a string XML
        $xmlString = $xml->asXML();

        Log::info("XML generado para DE ID {$documento->id}");

        return $xmlString;
    }
}
