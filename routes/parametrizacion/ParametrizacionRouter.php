<?php

use App\Http\Controllers\Parametrizacion\ParametrizacionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'parametrizacion',
    // 'middleware' => 'auth:api',
], function ($router) {
    Route::get('index', [ParametrizacionController::class, 'index']);
    Route::get('index/{DocumentMaster}', [ParametrizacionController::class, 'view']);
    Route::post('store', [ParametrizacionController::class, 'store']);
    Route::put('update/{DocumentMaster}', [ParametrizacionController::class, 'update']);
    Route::get('index/process/table', [ParametrizacionController::class, 'indexProcess']);
    Route::get('index/process/{uuid}', [ParametrizacionController::class, 'indexProcessView']);
    Route::post('store/proceso', [ParametrizacionController::class, 'storeProceso']);
    Route::put('update/proceso/{uuid}', [ParametrizacionController::class, 'updateProceso']);
    Route::get('index/subProcess/table', [ParametrizacionController::class, 'indexSubProcess']);
    Route::get('index/subProcess/{uuid}', [ParametrizacionController::class, 'indexSubProcessView']);
    Route::get('search/subproceso/{consulta}', [ParametrizacionController::class, 'searchSubproceso']);
    Route::post('store/subProceso', [ParametrizacionController::class, 'storeSubProceso']);
    Route::put('update/subProceso/{uuid}', [ParametrizacionController::class, 'updateSubProceso']);
});
