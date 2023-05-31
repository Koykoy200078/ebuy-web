<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authcontroller;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Auth::routes(['verify' => true]);

// Route::get('/',[App\Http\Controllers\Frontend\FrontendController::class, 'index']);
// Route::get('/collections', [App\Http\Controllers\Frontend\FrontendController::class, 'categories']);
// Route::get('/collections/{category_slug}', [App\Http\Controllers\Frontend\FrontendController::class, 'products'] );
// Route::get('/collections/{category_slug}/{product_slug}', [App\Http\Controllers\Frontend\FrontendController::class, 'productView'] );

Route::controller(App\Http\Controllers\Frontend\FrontendController::class)->group(function () {

    Route::get('/', 'index');
    Route::get('/collections', 'categories');
    Route::get('/collections/{category_slug}', 'products');
    Route::get('/collections/{category_slug}/{product_slug}', 'productView');
    Route::get('/about-us', 'aboutUs');

    //Comment System
    Route::post('/comments', [App\Http\Controllers\Frontend\CommentProductController::class, 'store']);
    Route::post('/delete-comment', [App\Http\Controllers\Frontend\CommentProductController::class, 'destroy']);

    Route::get('/new-arrivals', 'newArrival');
    Route::get('/featured-products', 'featuredProducts');

    Route::get('/search', 'searchProducts');
});

Route::get("/auth/github/redirect", [App\Http\Controllers\authcontroller::class, 'githubredirect'])->name('githublogin');
Route::get("/auth/github/callback", [App\Http\Controllers\authcontroller::class, 'githubcallback']);

Route::get("/auth/google/redirect", [App\Http\Controllers\authcontroller::class, 'googleredirect'])->name('googlelogin');
Route::get("/auth/google/callback", [App\Http\Controllers\authcontroller::class, 'googlecallback']);



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('wishlist', [App\Http\Controllers\Frontend\WishlistController::class, 'index']);
    Route::get('cart', [App\Http\Controllers\Frontend\CartController::class, 'index']);
    Route::get('checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index']);

    Route::controller(App\Http\Controllers\Frontend\ProductStatusrController::class)->group(function () {
        Route::get('product-status', 'index');
        Route::get('product-status/{orderId}', 'show');
        Route::put('product-status/{orderId}', 'updateOrderStatus');

        Route::get('product-status/invoice/{orderId}', 'viewInvoice');
        Route::get('product-status/invoice/{orderId}/generate', 'generateInvoice');

        Route::get('product-status/invoice/{orderId}/mail', 'mailInvoice');
    });
    Route::get('profile', [App\Http\Controllers\Frontend\UserController::class, 'index']);
    Route::post('profile', [App\Http\Controllers\Frontend\UserController::class, 'UpdateUserDetails']);

    Route::get('change-password', [App\Http\Controllers\Frontend\UserController::class, 'passwordCreate']);
    Route::post('change-password', [App\Http\Controllers\Frontend\UserController::class, 'changePassword']);

    // Route::get('product-status', [App\Http\Controllers\Frontend\ProductStatusrController::class, 'index']);


    Route::controller(App\Http\Controllers\Frontend\OrderController::class)->group(function () {
        Route::get('orders', 'index');
        Route::get('orders/{orderId}', 'show');
        Route::put('orders/{orderId}', 'updateOrderStatus');

        Route::get('invoice/{orderId}', 'viewInvoice');
        Route::get('invoice/{orderId}/generate', 'generateInvoice');

        Route::get('invoice/{orderId}/mail', 'mailInvoice');
    });


    //Product Route For Users
    Route::controller(App\Http\Controllers\Frontend\ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/products/create ', 'create');
        Route::post('/products', 'store');
        Route::get('/products/{product}/edit', 'edit');
        Route::put('/products/{product}', 'update');
        Route::get('products/{product_id}/delete', 'destroy');
        Route::get('product-image/{product_image_id}/delete', 'destroyImage');

        Route::post('product-color/{prod_color_id}', 'updateProdColorQty');
        Route::get('product-color/{prod_color_id}/delete', 'deleteProdColor');
    });
});

Route::get('thank-you', [App\Http\Controllers\Frontend\FrontendController::class, 'thankyou']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Admin Route
Route::prefix('admin')->middleware(['auth', 'IsAdmin', 'verified'])->group(function () {

    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index']);
    Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'store']);

    Route::controller(App\Http\Controllers\Admin\SliderController::class)->group(function () {
        Route::get('/sliders', 'index');
        Route::get('/sliders/create', 'create');
        Route::post('/sliders/create', 'store');
        Route::get('/sliders/{slider}/edit', 'edit');
        Route::put('/sliders/{slider}', 'update');
        Route::get('/sliders/{slider}/delete', 'destroy');
    });

    //Category Route
    Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
        Route::get('/category', 'index');
        Route::get('/category/create4', 'create');
        Route::post('/category', 'store');
        Route::get('/category/{category}/edit', 'edit');
        Route::put('/category/{category}', 'update');
    });

    //Product Route
    Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/products/create3 ', 'create');
        Route::post('/products', 'store');
        Route::get('/products/{product}/edit', 'edit');
        Route::put('/products/{product}', 'update');
        Route::get('products/{product_id}/delete', 'destroy');
        Route::get('product-image/{product_image_id}/delete', 'destroyImage');

        Route::post('product-color/{prod_color_id}', 'updateProdColorQty');
        Route::get('product-color/{prod_color_id}/delete', 'deleteProdColor');

        Route::get('/admin/products', 'index')->name('admin.products.index');
    });
    //Brand Route
    Route::get('/brands', App\Http\Livewire\Admin\Brand\Index::class);

    Route::controller(App\Http\Controllers\Admin\ColorController::class)->group(function () {
        Route::get('/colors', 'index');
        Route::get('/colors/create1', 'create');
        Route::post('/colors/create', 'store');
        Route::get('/colors/{color}/edit', 'edit');
        Route::put('/colors/{color_id}', 'update');
        Route::get('/colors/{color_id}/delete', 'destroy');
    });

    //Order Route
    Route::controller(App\Http\Controllers\Admin\OrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::get('/orders/{orderId}', 'show');
        Route::put('/orders/{orderId}', 'updateOrderStatus');

        Route::get('/invoice/{orderId}', 'viewInvoice');
        Route::get('/invoice/{orderId}/generate', 'generateInvoice');

        Route::get('/invoice/{orderId}/mail', 'mailInvoice');
    });

    Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/users/create2', 'create');
        Route::post('/users', 'store');
        Route::get('/users/{user_id}/edit', 'edit');
        Route::put('/users/{user_id}', 'update');

        Route::get('/users/{user_id}/delete', 'destroy');
    });

    Route::controller(App\Http\Controllers\Admin\ActivityLogController::class)->group(function () {
        Route::get('/activity-logs', 'index');
    



    });

    Route::controller(App\Http\Controllers\Admin\TransactionController::class)->group(function () {
        Route::get('/save-transaction', 'index');
        Route::get('/save-transaction/create7', 'create');
        Route::post('/save-transaction', 'store');
        Route::get('/save-transaction/{transaction}/edit', 'edit');
        Route::put('/save-transaction/{transaction}', 'update');
        Route::get('/save-transaction/{transaction}/delete', 'destroy');

        Route::get('save-transaction/{transaction}', 'viewInvoice');

        Route::get('save-transaction/{transaction}/mail', 'mailInvoice');

    });
});
