@extends('layouts.app')

@section('title', 'XML del Lote '.$lote->numero_lote)

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">
            XML del Lote {{ $lote->numero_lote }} (ID {{ $lote->id }})
        </h1>
    </div>

    {{-- DEBUG --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-md p-3 text-sm">
        <p><b>DEBUG:</b></p>
        <p>strlen($xmlEnviado): <b>{{ strlen($xmlEnviado ?? '') }}</b></p>
        <p>xmlRespuesta es null?: <b>{{ is_null($xmlRespuesta) ? 'SÍ' : 'NO' }}</b></p>
    </div>

    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-4 space-y-4">

        <div class="text-sm text-gray-700 space-y-1">
            <p><b>Empresa ID:</b> {{ $lote->empresa_id }}</p>
            <p><b>Estado:</b> {{ $lote->estado ?? 'N/A' }}</p>
            <p><b>Cantidad de documentos:</b> {{ $lote->documentos->count() }}</p>
            <p><b>Fecha envío:</b> {{ $lote->fecha_envio }}</p>
            <p><b>Fecha respuesta:</b> {{ $lote->fecha_respuesta }}</p>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">📤 XML Enviado / Generado</h3>

            @if (!empty($xmlEnviado))
                <pre class="bg-gray-900 text-green-100 p-4 rounded-md overflow-auto text-xs border border-gray-700"
                     style="max-height: 400px; white-space: pre-wrap;">
{{ $xmlEnviado }}
                </pre>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-3 rounded-md text-sm">
                    No hay contenido en <code>$xmlEnviado</code> (está vacío o null).
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">📥 XML Respuesta de SIFEN</h3>

            @if (!empty($xmlRespuesta))
                <pre class="bg-gray-900 text-blue-100 p-4 rounded-md overflow-auto text-xs border border-gray-700"
                     style="max-height: 400px; white-space: pre-wrap;">
{{ $xmlRespuesta }}
                </pre>
            @else
                <p class="text-sm text-gray-500 italic">Sin respuesta todavía…</p>
            @endif
        </div>

    </div>

</div>

@endsection
