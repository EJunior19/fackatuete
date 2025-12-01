<?php

namespace App\Services\Sifen;

use App\Models\Empresa;
use Illuminate\Support\Facades\Log;

class XMLBuilder
{
    public static function generar($de)
    {
        // ========================
        // VALIDACIÓN DE EMPRESA
        // ========================
        $empresa = $de->empresa ?? null;

        if (!$empresa && $de->empresa_id) {
            $empresa = Empresa::find($de->empresa_id);
        }

        if (!$empresa) {
            Log::error("XMLBuilder: Documento {$de->id} no tiene empresa asociada");
            throw new \RuntimeException("El documento no tiene empresa asociada.");
        }

        // ========================
        // DATOS RELACIONADOS
        // ========================
        // En tu sistema el "cliente" está dentro del propio Documento
        $timbrado = $de->timbrado;   // relación timbrado()
        $items    = $de->items;      // relación items()

        // ========================
        // CDC
        // ========================
        $cdc = CDCGenerator::generar($de);

        // ========================
        // Fecha ISO
        // ========================
        $fechaEmision = $de->fecha_emision->format('Y-m-d\TH:i:s');

        // ========================
        // Construcción del XML
        // ========================
        $xml = new \SimpleXMLElement('<rDE xmlns="http://ekuatia.set.gov.py/sifen/xsd"></rDE>');

        // ----------------- gCabDE -----------------
        $gCabDE = $xml->addChild("gCabDE");
        $gCabDE->addChild("IdCdc", $cdc);
        $gCabDE->addChild("dDVId", substr($cdc, -1));
        $gCabDE->addChild("dFecEmiDE", $fechaEmision);
        $gCabDE->addChild("dTiDE", 1); // Factura electrónica

        // ----------------- gOpeDE -----------------
        $gOpeDE = $gCabDE->addChild("gOpeDE");
        $gOpeDE->addChild("iTipEmi", 1);              // normal
        $gOpeDE->addChild("dDesdTipEmi", "Normal");

        // ----------------- gEmis (Emisor) -----------------
        $gEmis = $gCabDE->addChild("gEmis");
        $gEmis->addChild("dRucEm", $empresa->ruc);
        $gEmis->addChild("dDVEmi", $empresa->dv);
        $gEmis->addChild("dNomEmi", htmlspecialchars($empresa->razon_social));
        $gEmis->addChild("dDirEmi", htmlspecialchars($empresa->direccion ?? "SIN DIRECCIÓN"));

        // Establecimiento / Punto de expedición / Timbrado
        $gEmis->addChild("dEst", str_pad($de->establecimiento, 3, '0', STR_PAD_LEFT));
        $gEmis->addChild("dPunExp", str_pad($de->punto_expedicion, 3, '0', STR_PAD_LEFT));
        $gEmis->addChild("dNumTim", $timbrado->numero);
        $gEmis->addChild("dFeIniT", $timbrado->fecha_inicio);

        // ----------------- gDatRec (Receptor) -----------------
        $gDatRec = $gCabDE->addChild("gDatRec");
        $gDatRec->addChild("iNatRec", 1); // persona jurídica
        $gDatRec->addChild("iTiOpe", 1);  // operaciones internas

        // 👉 acá usamos los campos del propio Documento
        $gDatRec->addChild("dRucRec", $de->cliente_ruc ?? '0');
        $gDatRec->addChild("dDVRec", $de->cliente_dv ?? '0');
        $gDatRec->addChild("dNomRec", htmlspecialchars($de->cliente_nombre ?? 'SIN NOMBRE'));
        $gDatRec->addChild("dDirecRec", htmlspecialchars($de->cliente_direccion ?? 'SIN DIRECCIÓN'));

        // ----------------- gDtipDE -----------------
        $gDtipDE = $gCabDE->addChild("gDtipDE");
        $gDtipDE->addChild("iTipDoc", 1);
        $gDtipDE->addChild("dDesTipDoc", "FACTURA ELECTRÓNICA");

        // ----------------- gCamFE -----------------
        $gCamFE = $gCabDE->addChild("gCamFE");
        $gCamFE->addChild("dDirPaiRec", "PY");
        $gCamFE->addChild("dCondTiCam", 1); // contado (por ahora fijo)

        // ----------------- Items -----------------
        foreach ($items as $item) {
            $gCamItem = $xml->addChild("gCamItem");

            // Si no tenés código interno, podés usar el ID del ítem
            $gCamItem->addChild("dCodInt", $item->codigo ?? $item->id);
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

        // ----------------- Totales -----------------
        // OJO: acá uso los nombres de campos que vos mismo definiste en store()
        $gTotSub = $xml->addChild("gTotSub");
        $gTotSub->addChild("dSubExe", number_format($de->total_exenta ?? 0, 0, '.', ''));
        $gTotSub->addChild("dSub5",  number_format($de->total_gravada_5 ?? 0, 0, '.', ''));
        $gTotSub->addChild("dSub10", number_format($de->total_gravada_10 ?? 0, 0, '.', ''));
        $gTotSub->addChild("dTotBru", number_format($de->total_general ?? 0, 0, '.', ''));
        $gTotSub->addChild("dTotIVA", number_format($de->total_iva ?? 0, 0, '.', ''));
        $gTotSub->addChild("dTotGralOpe", number_format($de->total_general ?? 0, 0, '.', ''));

        // ----------------- XML final -----------------
        $xmlString = $xml->asXML();

        Log::info("XML generado para DE ID {$de->id}");

        return $xmlString;
    }
}
