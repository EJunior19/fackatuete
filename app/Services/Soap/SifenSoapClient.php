<?php

namespace App\Services\Soap;

class SifenSoapClient
{
    // Más adelante vamos a leer esto de config/services.php
    protected string $baseUrlTest = 'https://sifen-test.set.gov.py';
    protected string $baseUrlProd = 'https://sifen.set.gov.py';

    public function recibeLote(string $payload): string
    {
        // TODO: implementar SOAP real.
        // Por ahora simulamos una respuesta parecida a la del manual.
        return <<<XML
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope">
  <env:Body>
    <ns2:rResEnviLoteDe xmlns:ns2="http://ekuatia.set.gov.py/sifen/xsd">
      <ns2:dFecProc>2024-10-08T14:51:21-03:00</ns2:dFecProc>
      <ns2:dCodRes>0300</ns2:dCodRes>
      <ns2:dMsgRes>Lote recibido con éxito</ns2:dMsgRes>
      <ns2:dProtConsLote>11158097383597290</ns2:dProtConsLote>
    </ns2:rResEnviLoteDe>
  </env:Body>
</env:Envelope>
XML;
    }

    public function consultaLote(string $numeroLote): string
    {
        return <<<XML
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope">
  <env:Body>
    <ns2:rResEnviConsLoteDe xmlns:ns2="http://ekuatia.set.gov.py/sifen/xsd">
      <ns2:dCodResLot>0361</ns2:dCodResLot>
      <ns2:dMsgResLot>Lote {$numeroLote} en procesamiento</ns2:dMsgResLot>
    </ns2:rResEnviConsLoteDe>
  </env:Body>
</env:Envelope>
XML;
    }

    public function consultaCdc(string $cdc): string
    {
        return <<<XML
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope">
  <env:Body>
    <ns2:rEnviConsDeResponse xmlns:ns2="http://ekuatia.set.gov.py/sifen/xsd">
      <ns2:dCodRes>0420</ns2:dCodRes>
      <ns2:dMsgRes>Documento No Existe en SIFEN o ha sido Rechazado</ns2:dMsgRes>
    </ns2:rEnviConsDeResponse>
  </env:Body>
</env:Envelope>
XML;
    }
}
