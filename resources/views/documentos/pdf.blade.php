<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Documento Electrónico</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h1 {
            font-size: 15px;
            margin: 0;
        }

        .header {
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .empresa {
            font-size: 10px;
        }

        .datos {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            font-size: 10px;
        }

        .right { text-align: right; }
        .center { text-align: center; }

        .totales {
            margin-top: 10px;
            width: 100%;
        }

        .qr {
            margin-top: 18px;
            text-align: left;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 6px;
        }
    </style>
</head>
<body>

{{-- ENCABEZADO --}}
<div class="header">
    <h1>FACTURA ELECTRÓNICA</h1>
    <div class="empresa">
        <strong>{{ $documento->empresa->razon_social ?? '' }}</strong><br>
        RUC: {{ $documento->empresa->ruc ?? '' }}-{{ $documento->empresa->dv ?? '' }}
    </div>
</div>

{{-- DATOS DEL DOCUMENTO --}}
<div class="datos">
    <strong>CDC:</strong> {{ $documento->cdc }}<br>
    <strong>Número:</strong> {{ $documento->numero }}<br>
    <strong>Fecha de emisión:</strong>
    {{ \Carbon\Carbon::parse($documento->fecha_emision)->format('d/m/Y') }}
</div>

{{-- DATOS CLIENTE --}}
<div class="datos">
    <strong>Cliente:</strong> {{ $documento->cliente_nombre }}<br>
    <strong>RUC:</strong> {{ $documento->cliente_ruc }}-{{ $documento->cliente_dv }}
</div>

{{-- ÍTEMS --}}
<table>
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Cant.</th>
            <th>Precio</th>
            <th>IVA</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($documento->items as $item)
            <tr>
                <td>{{ $item->descripcion }}</td>
                <td class="right">{{ number_format($item->cantidad, 2, ',', '.') }}</td>
                <td class="right">{{ number_format($item->precio_unit, 0, ',', '.') }}</td>
                <td class="center">{{ $item->iva }}%</td>
                <td class="right">{{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- TOTALES --}}
<table class="totales">
    <tr>
        <td>Gravada 10%</td>
        <td class="right">{{ number_format($documento->total_gravada_10, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Gravada 5%</td>
        <td class="right">{{ number_format($documento->total_gravada_5, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Exenta</td>
        <td class="right">{{ number_format($documento->total_exenta, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Total</th>
        <th class="right">{{ number_format($documento->total_general, 0, ',', '.') }}</th>
    </tr>
</table>

{{-- QR SET --}}
@if($qrSvg)
<div class="qr">
    <strong>Consulta SET</strong><br>
    Escanee el código QR para verificar el documento en la SET<br><br>
    {!! $qrSvg !!}
</div>
@endif

<div class="footer">
    Documento electrónico generado conforme a la normativa SIFEN – SET
</div>

</body>
</html>
