<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Controllers
use App\Http\Controllers\Api\DocumentoApiController;
use App\Http\Controllers\Api\LoteApiController;
use App\Http\Controllers\Api\EventoApiController;
use App\Http\Controllers\Api\DocumentoErpController; 

/*
|--------------------------------------------------------------------------
| API – AUTENTICADA CON SANCTUM
|--------------------------------------------------------------------------
|
| Todas las rutas dentro de /api/v1 están protegidas con auth:sanctum.
| Los clientes externos deberán usar:
|
|   Authorization: Bearer {TOKEN}
|
*/

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DOCUMENTOS (API)
        |--------------------------------------------------------------------------
        |
        | - Listar documentos
        | - Crear documento
        | - Ver detalle
        | - Firmar XML
        | - Consultar CDC en SIFEN
        |
        */

        Route::get('/documentos',                 [DocumentoApiController::class, 'index']);
        Route::post('/documentos',                [DocumentoApiController::class, 'store']);
        Route::get('/documentos/{doc}',           [DocumentoApiController::class, 'show']);
        Route::post('/documentos/{doc}/firmar',   [DocumentoApiController::class, 'firmar']);
        Route::get('/documentos/{doc}/cdc',       [DocumentoApiController::class, 'consultarCdc']);

        Route::post('/documentos/desde-erp',      [DocumentoErpController::class, 'store']);
        /*
        |--------------------------------------------------------------------------
        | LOTES (API)
        |--------------------------------------------------------------------------
        |
        | - Listar lotes
        | - Crear lote con lista de documentos
        | - Ver detalle lote
        | - Enviar lote a SIFEN
        | - Consultar estado de lote
        |
        */

        Route::get('/lotes',                      [LoteApiController::class, 'index']);
        Route::post('/lotes',                     [LoteApiController::class, 'store']);
        Route::get('/lotes/{lote}',               [LoteApiController::class, 'show']);
        Route::post('/lotes/{lote}/enviar',       [LoteApiController::class, 'enviar']);
        Route::get('/lotes/{lote}/consultar',     [LoteApiController::class, 'consultar']);

        /*
        |--------------------------------------------------------------------------
        | EVENTOS (API – solo lectura)
        |--------------------------------------------------------------------------
        |
        | - Listar eventos con filtros
        | - Ver un evento
        |
        */

        Route::get('/eventos',                    [EventoApiController::class, 'index']);
        Route::get('/eventos/{evento}',           [EventoApiController::class, 'show']);
    });


Route::middleware('auth:sanctum')->get('/test', function (Request $request) {
    return response()->json([
        'ok'   => true,
        'user' => $request->user(),
    ]);
});
