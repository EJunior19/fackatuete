<?php

namespace App\Services\Cdc;

class CdcGenerator
{
    public static function generar(array $data): string
    {
        $tipoDocumento      = $data['tipo_documento'];
        $establecimiento    = str_pad($data['establecimiento'], 3, '0', STR_PAD_LEFT);
        $puntoExpedicion    = str_pad($data['punto_expedicion'], 3, '0', STR_PAD_LEFT);
        $numeroDocumento    = str_pad($data['numero'], 8, '0', STR_PAD_LEFT);
        $tipoContribuyente  = $data['tipo_contribuyente'];
        $ruc                = str_pad($data['ruc'], 8, '0', STR_PAD_LEFT);
        $dvRuc              = $data['dv_ruc'];
        $fecha              = $data['fecha'];
        $tipoEmision        = $data['tipo_emision'];
        $controlInterno     = str_pad($data['control'], 8, '0', STR_PAD_LEFT);

        $cadena = $tipoDocumento
            . $establecimiento
            . $puntoExpedicion
            . $numeroDocumento
            . $tipoContribuyente
            . $ruc
            . $dvRuc
            . $fecha
            . $tipoEmision
            . $controlInterno;

        $dvFinal = self::calcularDv($cadena);

        return $cadena . $dvFinal;
    }

    public static function calcularDv(string $cadena): int
    {
        $multiplicador = 2;
        $suma = 0;

        for ($i = strlen($cadena) - 1; $i >= 0; $i--) {
            $suma += intval($cadena[$i]) * $multiplicador;
            $multiplicador++;

            if ($multiplicador > 11) {
                $multiplicador = 2;
            }
        }

        $resto = $suma % 11;
        $dv = 11 - $resto;

        return ($dv >= 10) ? 0 : $dv;
    }

    public static function validar(string $cdc): bool
    {
        if (strlen($cdc) !== 44) {
            return false;
        }

        $cuerpo = substr($cdc, 0, 43);
        $dv = substr($cdc, -1);

        return intval($dv) === self::calcularDv($cuerpo);
    }
}
