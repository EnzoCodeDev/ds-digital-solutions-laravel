<?php

use App\Http\Controllers\Configuration\configurationProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'configuration',
    'middleware' => 'auth:api',
], function ($router) {
    Route::post('update/profile/{uuid}', [configurationProfile::class, 'updateProfile']);
    Route::post('update/profile/password/{uuid}', [configurationProfile::class, 'updatePassword']);
    Route::post('searchInfo/{uuid}', [configurationProfile::class, 'updateInfoSearch']);
    // Route::get('search/{consulta}', [DocumentMasterController::class, 'search']);
    // Route::get('index/{DocumentMaster}', [DocumentMasterController::class, 'view']);
    // Route::post('store', [DocumentMasterController::class, 'store']);
//     Route::post('update/{DocumentMaster}', [DocumentMasterController::class, 'update']);
//     // Route::delete('delete/{DocumentAdmin}', [DocumentAdminController::class, 'destroy']);
});
