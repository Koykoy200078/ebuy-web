<?php

use App\Http\Controllers\api\AddToCartController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\SearchController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;


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

        Route::get('/user/info', [AuthController::class, 'me']);

        // Product
        // Route::get('/categories/{category_slug}/products/{product_slug}', [ProductController::class, 'productView']);

        Route::get('/products/index', [ProductController::class, 'index']);
        // Route::get('/products/view', [ProductController::class, 'view']);
        Route::post('/products/create', [ProductController::class, 'store']);
        // Route::get('/products/{product_id}', [ProductController::class, 'showProduct']);
        Route::get('/products/{category_slug}/{product_slug}', [ProductController::class, 'productView']);

        Route::put('/products/update/{product_id}', [ProductController::class, 'update']);
        // Route::post('/products/{product_id}/update-images', 'ProductController@addImages');


        Route::delete('product_images/{product_image_id}', [ProductController::class, 'destroyImage']);
        Route::delete('products/{product_id}', [ProductController::class, 'destroy']);

        Route::put('/product-colors/{prod_color_id}', [ProductController::class, 'updateProdColorQty']);
        Route::delete('/product-colors/{prod_color_id}', [ProductController::class, 'deleteProdColor']);





        // Category
        Route::get('/categories', [CategoryController::class, 'categories']);

        // User
        Route::put('/user/details', [UserController::class, 'updateUserDetails']);

        // Search
        Route::get('/search', [SearchController::class, 'searchProducts']);

        // Cart Count
        Route::get('/cart/count', [AddToCartController::class, 'cart']);
        Route::get('/cart', [AddToCartController::class, 'cartShow']);
        Route::put('/cart/{cartId}/decrement', [CartController::class, 'decrementQuantity']);
        Route::delete('/cart/{cartId}', [CartController::class, 'removeCartItem']);
        Route::put('/cart/{cartId}/increment', [CartController::class, 'incrementQuantity']);
    });
});
