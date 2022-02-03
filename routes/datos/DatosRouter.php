<?php

use App\Http\Controllers\Datos\DatosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'datos',
    'middleware' => 'auth:api',
], function ($router) {
    Route::get('index', [DatosController::class, 'index']);
    Route::get('search/{consulta}', [DatosController::class, 'search']);
    Route::get('index/{DocumentMaster}', [DatosController::class, 'view']);
    Route::post('store', [DatosController::class, 'store']);
    Route::get('index/process/all', [DatosController::class, 'indexProceso']);
    Route::get('index/Subprocess/{id}', [DatosController::class, 'indexSubProceso']);
    Route::post('download/file', [DatosController::class, 'downloadFile']);
});
