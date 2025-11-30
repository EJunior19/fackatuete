<?php

namespace App\Services\XML;

class LoteGenerator
{
    public static function generarLote(string $xmlFirmado, int $idLote = null): string
    {
        $idLote = $idLote ?? rand(100000, 999999);

        return <<<XML
<rEnviDe xmlns="http://ekuatia.set.gov.py/sifen/xsd">
    <dId>{$idLote}</dId>
    <dEnvLote>1</dEnvLote>
    {$xmlFirmado}
</rEnviDe>
XML;
    }
}
