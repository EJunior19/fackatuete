<?php

namespace App\Services\Sifen;

use App\Models\Documento;
use App\Models\Lote;
use App\Models\SifenLog;
use App\Models\Evento;
use App\Services\XML\DEBuilder;
use App\Services\Cdc\CdcGenerator;
use App\Services\Firma\XmlSigner;
use App\Services\Lote\LoteService;
use App\Services\Soap\SifenSoapClient;
use Throwable;

class SifenClient
{
    public function __construct(
        protected DEBuilder $xmlBuilder,
        protected CdcGenerator $cdcGenerator,
        protected XmlSigner $signer,
        protected LoteService $loteService,
        protected SifenSoapClient $soapClient,
    ) {}

    /**
     * Registrar un evento SIFEN
     */
    private function registrarEvento(array $data): void
    {
        Evento::create([
            'documento_id' => $data['documento_id'] ?? null,
            'lote_id'      => $data['lote_id'] ?? null,
            'codigo'       => $data['codigo'] ?? '',
            'tipo'         => $data['tipo'] ?? '',
            'descripcion'  => $data['descripcion'] ?? '',
            'mensaje'      => $data['mensaje'] ?? '',
            'xml'          => $data['xml'] ?? null,
        ]);
    }

    /**
     * Generar, firmar y preparar un DE (pero sin enviar todavía)
     */
    public function prepararDocumento(Documento $documento): Documento
    {
        // 1) Generar CDC
        $documento->cdc = $this->cdcGenerator->generar([
            'tipo_documento'     => $documento->tipo_documento,
            'establecimiento'    => $documento->establecimiento,
            'punto_expedicion'   => $documento->punto_expedicion,
            'numero'             => $documento->numero,
            'tipo_contribuyente' => 1,
            'ruc'                => $documento->empresa->ruc,
            'dv_ruc'             => $documento->empresa->dv,
            'fecha'              => str_replace('-', '', $documento->fecha),
            'tipo_emision'       => 1,
            'control'            => 1,
        ]);

        // 2) Generar XML
        $xml = $this->xmlBuilder->buildFromDocumento($documento);
        $documento->xml_generado = $xml;

        // 3) Firmar
        $firmado = $this->signer->sign(
            $xml,
            $documento->empresa->cert_publico,
            $documento->empresa->cert_privado,
            $documento->empresa->cert_password
        );

        $documento->xml_firmado = $firmado;

        $documento->save();

        return $documento;
    }

    /**
     * Enviar un lote de documentos a SIFEN
     */
    public function enviarLote(Lote $lote): array
    {
        try {
            $payload = $this->loteService->buildLotePayload($lote);

            $responseXml = $this->soapClient->recibeLote($payload);

            // Log
            SifenLog::create([
                'accion'    => 'envio_lote',
                'resultado' => 'success',
                'request'   => $payload,
                'response'  => $responseXml,
            ]);

            // Extraer datos XML
            preg_match('/<dCodRes>(.*?)<\/dCodRes>/', $responseXml, $cod);
            preg_match('/<dMsgRes>(.*?)<\/dMsgRes>/', $responseXml, $msg);
            preg_match('/<dProtConsLote>(.*?)<\/dProtConsLote>/', $responseXml, $prot);

            $codigo = $cod[1] ?? '0000';
            $mensaje = $msg[1] ?? 'Sin mensaje';
            $protocolo = $prot[1] ?? null;

            // Guardar evento
            $this->registrarEvento([
                'lote_id'     => $lote->id,
                'codigo'      => $codigo,
                'tipo'        => 'envio_lote',
                'descripcion' => $mensaje,
                'mensaje'     => "Protocolo: " . ($protocolo ?? 'N/A'),
                'xml'         => $responseXml,
            ]);

            return [
                'ok'        => true,
                'codigo'    => $codigo,
                'mensaje'   => $mensaje,
                'protocolo' => $protocolo,
                'raw_xml'   => $responseXml,
            ];

        } catch (Throwable $e) {

            $this->registrarEvento([
                'lote_id'     => $lote->id,
                'codigo'      => 'ERROR',
                'tipo'        => 'envio_lote_error',
                'descripcion' => 'Error al enviar lote',
                'mensaje'     => $e->getMessage(),
                'xml'         => null,
            ]);

            SifenLog::create([
                'accion'    => 'envio_lote',
                'resultado' => 'error',
                'error'     => $e->getMessage(),
            ]);

            return [
                'ok'    => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Consultar estado de un lote
     */
    public function consultarLote(string $numeroLote): array
    {
        $response = $this->soapClient->consultaLote($numeroLote);

        SifenLog::create([
            'accion'    => 'consulta_lote',
            'resultado' => 'success',
            'request'   => $numeroLote,
            'response'  => $response,
        ]);

        // Extraer códigos
        preg_match('/<dCodResLot>(.*?)<\/dCodResLot>/', $response, $cod);
        preg_match('/<dMsgResLot>(.*?)<\/dMsgResLot>/', $response, $msg);

        $codigo = $cod[1] ?? '---';
        $mensaje = $msg[1] ?? 'Sin mensaje';

        $lote = Lote::where('numero_lote', $numeroLote)->first();

        $this->registrarEvento([
            'lote_id'     => $lote->id ?? null,
            'codigo'      => $codigo,
            'tipo'        => 'consulta_lote',
            'descripcion' => $mensaje,
            'mensaje'     => $mensaje,
            'xml'         => $response,
        ]);

        return [
            'ok'      => true,
            'codigo'  => $codigo,
            'mensaje' => $mensaje,
            'raw_xml' => $response,
        ];
    }

    /**
     * Consultar CDC de un documento individual
     */
    public function consultarCdc(string $cdc): array
    {
        $response = $this->soapClient->consultaCdc($cdc);

        SifenLog::create([
            'accion'    => 'consulta_cdc',
            'resultado' => 'success',
            'request'   => $cdc,
            'response'  => $response,
        ]);

        preg_match('/<dCodRes>(.*?)<\/dCodRes>/', $response, $cod);
        preg_match('/<dMsgRes>(.*?)<\/dMsgRes>/', $response, $msg);

        $codigo = $cod[1] ?? '---';
        $mensaje = $msg[1] ?? 'Sin mensaje';

        $doc = Documento::where('cdc', $cdc)->first();

        $this->registrarEvento([
            'documento_id' => $doc->id ?? null,
            'codigo'       => $codigo,
            'tipo'         => 'consulta_cdc',
            'descripcion'  => $mensaje,
            'mensaje'      => $mensaje,
            'xml'          => $response,
        ]);

        return [
            'ok'      => true,
            'codigo'  => $codigo,
            'mensaje' => $mensaje,
            'raw_xml' => $response,
        ];
    }
}
