<?php

namespace App\Services\Sifen;

class SifenParser
{
    public static function parse(string $xml): array
    {
        $sxml = simplexml_load_string($xml, "SimpleXMLElement", 0, "soapenv", true);

        $body = $sxml->children("soapenv", true)->Body;
        $rRetEnviDe = $body->children("","")->rRetEnviDe;

        return [
            'codigo' => (string) $rRetEnviDe->dCodRes,
            'mensaje' => (string) $rRetEnviDe->dMsgRes,
            'lote_id' => (string) $rRetEnviDe->dId,
        ];
    }
}
