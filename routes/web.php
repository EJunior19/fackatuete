<?php

use Illuminate\Support\Facades\Route;

// Controllers principales
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\Sifen\LotesController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\UsuarioController;

// SIFEN
use App\Http\Controllers\Sifen\ClienteController;
use App\Http\Controllers\Sifen\EventoController;
use App\Http\Controllers\Sifen\SifenController;

/*
|--------------------------------------------------------------------------
| RUTAS AUTENTICADAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | AUDITORÍA
    |--------------------------------------------------------------------------
    */
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');

    /*
    |--------------------------------------------------------------------------
    | DOCUMENTOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('documentos')->name('documentos.')->group(function () {

        Route::get('/', [DocumentosController::class, 'index'])->name('index');
        Route::get('/crear', [DocumentosController::class, 'create'])->name('create');
        Route::post('/', [DocumentosController::class, 'store'])->name('store');

        Route::get('/{id}', [DocumentosController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [DocumentosController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DocumentosController::class, 'update'])->name('update');
        Route::delete('/{id}', [DocumentosController::class, 'destroy'])->name('destroy');

        // Acciones SIFEN
        Route::post('/{id}/enviar', [DocumentosController::class, 'enviar'])->name('enviar');
        Route::get('/{id}/pdf', [DocumentosController::class, 'pdf'])->name('pdf');
        Route::get('/{id}/xml', [DocumentosController::class, 'xml'])->name('xml');
        Route::get('/{id}/firmar', [DocumentosController::class, 'firmar'])->name('firmar');
    });

    /*
    |--------------------------------------------------------------------------
    | LOTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('lotes')->name('lotes.')->group(function () {
        Route::get('/', [LotesController::class, 'index'])->name('index');
        Route::get('/{id}', [LotesController::class, 'show'])->name('show');
        Route::post('/{id}/enviar', [LotesController::class, 'enviar'])->name('enviar');
        Route::get('/{id}/consultar', [LotesController::class, 'consultar'])->name('consultar');
    });

    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
    */
    Route::resource('usuarios', UsuarioController::class);

    /*
    |--------------------------------------------------------------------------
    | EVENTOS SIFEN
    |--------------------------------------------------------------------------
    */
    Route::prefix('eventos')->name('eventos.')->group(function () {
        Route::get('/', [EventoController::class, 'index'])->name('index');
        Route::get('/crear', [EventoController::class, 'create'])->name('create');
        Route::post('/', [EventoController::class, 'store'])->name('store');
        Route::get('/{id}', [EventoController::class, 'show'])->name('show');
    });

    /*
    |--------------------------------------------------------------------------
    | CLIENTES
    |--------------------------------------------------------------------------
    */
    Route::resource('clientes', ClienteController::class);
    Route::get('/api/clientes/buscar', [ClienteController::class, 'buscar'])
        ->name('clientes.buscar');

    /*
    |--------------------------------------------------------------------------
    | PRODUCTOS
    |--------------------------------------------------------------------------
    */
    Route::resource('productos', ProductoController::class);
    Route::post('/productos/{producto}/desactivar', [ProductoController::class, 'desactivar'])
        ->name('productos.desactivar');
    Route::post('/productos/{producto}/activar', [ProductoController::class, 'activar'])
        ->name('productos.activar');
    Route::get('/api/productos/buscar', [ProductoController::class, 'buscar'])
        ->name('api.productos.buscar');

    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN
    |--------------------------------------------------------------------------
    */
    Route::prefix('config')->name('config.')->group(function () {
        Route::get('/empresa', [EmpresaController::class, 'edit'])->name('empresa');
        Route::post('/empresa', [EmpresaController::class, 'update'])->name('empresa.update');

        Route::get('/certificados', [EmpresaController::class, 'certificados'])->name('certificados');
        Route::post('/certificados', [EmpresaController::class, 'guardarCertificados'])
            ->name('certificados.update');
    });

    /*
    |--------------------------------------------------------------------------
    | SIFEN TEST
    |--------------------------------------------------------------------------
    */
    Route::prefix('sifen')->group(function () {
        Route::get('/test', [SifenController::class, 'test']);
        Route::get('/demo/preparar', [SifenController::class, 'demoPrepararDocumento']);
        Route::get('/demo/enviar-lote', [SifenController::class, 'demoEnviarLote']);
    });

});

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| 404 FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
