<?php

use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        Route::get('/user', [AuthController::class, 'me']);

        // Product
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/view', [ProductController::class, 'create']);
        Route::post('/products/create', [ProductController::class, 'store']);

        // Category
        Route::get('/categories', [CategoryController::class, 'index']);
    });
});
