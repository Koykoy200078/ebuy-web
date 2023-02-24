<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::controller(AuthController::class)->group(function () {
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::get('me', 'me');
//     Route::post('logout', 'logout');
//     Route::post('refresh', 'refresh');
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware('jwt.auth')->post('/logout', [AuthController::class, 'logout']);
    Route::middleware('auth')->post('/refresh', [AuthController::class, 'refresh']);
    Route::middleware('jwt.auth')->get('/user', [AuthController::class, 'me']);
});
