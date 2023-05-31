<?php

use App\Http\Controllers\api\AddToCartController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CheckoutController;
use App\Http\Controllers\api\MyProductsController;
use App\Http\Controllers\api\OrdersController;
use App\Http\Controllers\api\SearchController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\WishlistController;
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

        Route::post('/forgot/password', [AuthController::class, 'sendResetLinkEmail']);

        Route::get('/google/redirect', [AuthController::class, 'googleRedirect']);
        Route::get('/google/callback', [AuthController::class, 'googleCallback']);
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user/info', [AuthController::class, 'me']);

        // Product
        Route::get('/products/new-arrival', [ProductController::class, 'newArrival']);
        Route::get('/products/featured-products', [ProductController::class, 'featuredProducts']);

        Route::get('/products/sliders', [ProductController::class, 'getSliders']);
        Route::get('/products/newArrival', [ProductController::class, 'getNewArrivalProducts']);
        Route::get('/products/trending', [ProductController::class, 'getTrendingProducts']);

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


        // myProduct
        Route::get('/myProducts', [MyProductsController::class, 'indexData']);

        // Category
        Route::get('/categories', [CategoryController::class, 'categories']);
        Route::get(
            '/categories/products/{category_slug}',
            [CategoryController::class, 'show']
        );

        // User
        Route::get('/user/details', [UserController::class, 'getUserDetails']);
        Route::put('/user/details/update', [UserController::class, 'updateUserDetails']);
        Route::put('/change-password', [UserController::class, 'changePassword']);

        // Search
        Route::get('/search', [SearchController::class, 'searchProducts']);

        // Product Count
        Route::get('/user/item/count', [ProductController::class, 'productCount']);

        // Wishlist
        Route::get('/wishlist/count', [WishlistController::class, 'wishlistCount']); // count
        Route::get('/wishlist', [WishlistController::class, 'wishlistView']); // view
        Route::post('/wishlist/newAdd/{productId}', [WishlistController::class, 'addToWishlist']); // add
        Route::delete('/wishlist/{wishlistId}', [WishlistController::class, 'destroy']); // delete

        // Cart Count
        Route::get('/cart/count', [AddToCartController::class, 'cart']);
        Route::get('/cart', [AddToCartController::class, 'cartShow']);

        Route::post('/products/cart/{productId}', [AddToCartController::class, 'addToCart']);


        Route::put('/cart/decrementQuantity/{cartId}', [AddToCartController::class, 'decrementQuantity']);
        Route::delete('/cart/remove/{cartId}', [AddToCartController::class, 'removeCartItem']);
        Route::put('/cart/incrementQuantity/{cartId}', [AddToCartController::class, 'incrementQuantity']);

        // Checkout
        Route::post('/add-orders', [CheckoutController::class, 'store']);

        // Orders
        Route::get('/orders', [OrdersController::class, 'index']);
        Route::get('/orders/{orderId}', [OrdersController::class, 'show']);
        Route::put('/orders/{orderId}/status', [OrdersController::class, 'updateOrderStatus']);
        Route::get('/orders/{orderId}/invoice', [OrdersController::class, 'viewInvoice']);
        Route::post('/orders/{orderId}/invoice', [OrdersController::class, 'mailInvoice']);

        // test
        Route::get('/sms', [AuthController::class, 'sms']);
    });
});
