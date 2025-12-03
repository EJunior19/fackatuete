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
        // Aseguramos que tenga ID en BD (por las dudas)
        if (! $documento->exists) {
            $documento->save();
        }

        // Solo generamos CDC si aún no tiene
        if (empty($documento->cdc)) {

            // Usamos el ID del documento como base del control
            $controlBase = $documento->id ?? random_int(1, 99999999);

            $documento->cdc = $this->cdcGenerator->generar([
                'tipo_documento'     => $documento->tipo_documento,
                'establecimiento'    => $documento->establecimiento,
                'punto_expedicion'   => $documento->punto_expedicion,
                'numero'             => $documento->numero,            // tu correlativo
                'tipo_contribuyente' => 1,
                'ruc'                => $documento->empresa->ruc,
                'dv_ruc'             => $documento->empresa->dv,
                'fecha'              => str_replace('-', '', $documento->fecha),
                'tipo_emision'       => 1,
                // 👇 ahora es único por documento
                'control'            => $controlBase,
            ]);

            // Evento CDC generado
            $this->registrarEvento([
                'documento_id' => $documento->id,
                'codigo'       => 'DE_CDC_GENERADO',
                'tipo'         => 'preparar_documento',
                'descripcion'  => 'CDC generado para el documento',
                'mensaje'      => $documento->cdc,
            ]);
        }

        // 2) Generar XML
        $xml = $this->xmlBuilder->buildFromDocumento($documento);
        $documento->xml_generado = $xml;

        $this->registrarEvento([
            'documento_id' => $documento->id,
            'codigo'       => 'DE_XML_GENERADO',
            'tipo'         => 'preparar_documento',
            'descripcion'  => 'XML generado para el documento',
            'mensaje'      => 'XML generado correctamente',
            'xml'          => $xml,
        ]);

        // 3) Firmar
        $firmado = $this->signer->sign(
            $xml,
            $documento->empresa->cert_publico,
            $documento->empresa->cert_privado,
            $documento->empresa->cert_password
        );

        $documento->xml_firmado = $firmado;
        $documento->save();

        $this->registrarEvento([
            'documento_id' => $documento->id,
            'codigo'       => 'DE_XML_FIRMADO',
            'tipo'         => 'firma_xml',
            'descripcion'  => 'XML firmado correctamente',
            'mensaje'      => 'XML firmado para envío a lote',
            'xml'          => $firmado,
        ]);

        return $documento;
    }


    /**
     * Enviar un lote de documentos a SIFEN
     */
    public function enviarLote(Lote $lote): array
    {
        try {
            // 1) Construir el XML del lote a partir de los DE firmados
            $payload = $this->loteService->buildLotePayload($lote);

            // 2) Guardar en la tabla lotes como "xml_enviado"
            $lote->xml_enviado   = $payload;
            $lote->fecha_envio   = now();
            $lote->estado        = 'enviado'; // o 'pendiente_respuesta' si preferís ese nombre
            $lote->save();

            // 3) Enviar al SIFEN vía SOAP
            $responseXml = $this->soapClient->recibeLote($payload);

            // 4) Log técnico
            SifenLog::create([
                'accion'    => 'envio_lote',
                'resultado' => 'success',
                'request'   => $payload,
                'response'  => $responseXml,
            ]);

            // 5) Extraer datos del XML de respuesta
            preg_match('/<dCodRes>(.*?)<\/dCodRes>/', $responseXml, $cod);
            preg_match('/<dMsgRes>(.*?)<\/dMsgRes>/', $responseXml, $msg);
            preg_match('/<dProtConsLote>(.*?)<\/dProtConsLote>/', $responseXml, $prot);

            $codigo    = $cod[1] ?? '0000';
            $mensaje   = $msg[1] ?? 'Sin mensaje';
            $protocolo = $prot[1] ?? null;

            // 6) Actualizar el lote con la respuesta
            $lote->xml_respuesta   = $responseXml;
            $lote->fecha_respuesta = now();
            $lote->respuesta       = $mensaje;

            // Si querés marcar un estado según código SIFEN:
            // 0300 = recibido OK (ejemplo típico)
            if ($codigo === '0300') {
                $lote->estado = 'recibido'; // o 'procesado', como uses
            } else {
                $lote->estado = 'error_envio'; // o 'rechazado', etc.
            }

            $lote->save();

            // 7) Guardar evento funcional
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

            // Evento funcional de error
            $this->registrarEvento([
                'lote_id'     => $lote->id ?? null,
                'codigo'      => 'ERROR',
                'tipo'        => 'envio_lote_error',
                'descripcion' => 'Error al enviar lote',
                'mensaje'     => $e->getMessage(),
                'xml'         => null,
            ]);

            // Log técnico
            SifenLog::create([
                'accion'    => 'envio_lote',
                'resultado' => 'error',
                'error'     => $e->getMessage(),
            ]);

            // Marcar el lote como error también (si existe)
            try {
                $lote->estado    = 'error_envio';
                $lote->respuesta = $e->getMessage();
                $lote->save();
            } catch (\Throwable $ignored) {
                // por si vino un lote sin persistir
            }

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

        // Extraer códigos de la respuesta
        preg_match('/<dCodResLot>(.*?)<\/dCodResLot>/', $response, $cod);
        preg_match('/<dMsgResLot>(.*?)<\/dMsgResLot>/', $response, $msg);

        $codigo  = $cod[1] ?? '---';
        $mensaje = $msg[1] ?? 'Sin mensaje';

        $lote = Lote::where('numero_lote', $numeroLote)->first();

        if ($lote) {
            // Guardamos la última respuesta de consulta también
            $lote->xml_respuesta   = $response;
            $lote->fecha_respuesta = now();
            $lote->respuesta       = $mensaje;

            // Podés mapear el estado según código que devuelva SIFEN
            if ($codigo === '0300') {
                $lote->estado = 'aceptado'; // o 'aprobado', como uses en tu UI
            } else {
                $lote->estado = 'rechazado'; // o 'observado', etc.
            }

            $lote->save();
        }

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
