<?php

namespace App\Services\Sifen;

class SifenSoap
{
    public static function wrap(string $loteXml): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope">
  <soapenv:Header/>
  <soapenv:Body>
      {$loteXml}
  </soapenv:Body>
</soapenv:Envelope>
XML;
    }
}
