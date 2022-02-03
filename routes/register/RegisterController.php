<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Register\RegisterController;

//Configuracion con la auth del token inicion de seccion, registro,
// refresh del token y logout
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [RegisterController::class, 'register']);
});
