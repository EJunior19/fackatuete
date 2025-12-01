<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $documento->numero }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 4px; }
        th { background: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>FACTURA ELECTRÓNICA</h1>

    <p><strong>Empresa:</strong> {{ $documento->empresa->razon_social ?? '' }}</p>
    <p><strong>RUC:</strong> {{ $documento->empresa->ruc ?? '' }}-{{ $documento->empresa->dv ?? '' }}</p>
    <p><strong>Cliente:</strong> {{ $documento->cliente_nombre }}</p>
    <p><strong>RUC Cliente:</strong> {{ $documento->cliente_ruc }}-{{ $documento->cliente_dv }}</p>
    <p><strong>Fecha de emisión:</strong> {{ $documento->fecha_emision }}</p>

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
        @foreach ($documento->items as $item)
            <tr>
                <td>{{ $item->descripcion }}</td>
                <td class="text-right">{{ number_format($item->cantidad, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->precio_unit, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->iva }}%</td>
                <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p class="text-right">
        <strong>Total documento:</strong>
        {{ number_format($documento->total_general, 0, ',', '.') }} Gs.
    </p>
</body>
</html>
