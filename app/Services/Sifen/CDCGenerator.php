<?php

namespace App\Services\Sifen;

class CDCGenerator
{
    public static function generar($documento)
    {
        $ruc        = str_pad($documento->empresa->ruc, 8, '0', STR_PAD_LEFT);
        $dvRuc      = $documento->empresa->dv;
        $tipo       = $documento->tipo_documento === 'FE' ? '01' : '00';

        $estable    = $documento->establecimiento;
        $punto      = $documento->punto_expedicion;
        $numero     = $documento->numero;

        $timbrado   = $documento->timbrado->numero;
        $fecha      = now()->format('Ymd');

        $tipoContrib = '1'; // formal
        $normalizado = "{$ruc}{$dvRuc}{$tipo}{$estable}{$punto}{$numero}{$timbrado}{$fecha}{$tipoContrib}";

        $dv = self::modulo11($normalizado);

        return $normalizado . $dv;
    }

    private static function modulo11($cadena)
    {
        $mult = [2,3,4,5,6,7];
        $i = 0;
        $suma = 0;

        for ($c = strlen($cadena) - 1; $c >= 0; $c--) {
            $digit = intval($cadena[$c]);
            $suma += $digit * $mult[$i];
            $i = ($i + 1) % count($mult);
        }

        $resto = $suma % 11;
        $dv = ($resto == 0 || $resto == 1) ? 0 : 11 - $resto;

        return $dv;
    }
}
