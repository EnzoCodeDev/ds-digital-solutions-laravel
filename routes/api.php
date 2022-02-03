<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Configuracion con la auth del token inicion de seccion, registro,
// refresh del token y logout
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('refresh',  [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
Route::resource('users', UserController::class);
