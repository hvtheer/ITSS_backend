<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//　Routes accessible to authentication
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

// Routes accessible to all authenticated users
Route::middleware('auth:api')->group(function () {

    // Routes accessible to admins
    Route::middleware('role:admin')->prefix('v1')->group(function () {
        Route::resource('/users','App\Http\Controllers\API\UserController');
        Route::resource('/categories','App\Http\Controllers\API\CategoryController');
        Route::resource('/coupons','App\Http\Controllers\API\CouponController');
        Route::resource('/customers','App\Http\Controllers\API\CustomerController');
        Route::resource('/orders','App\Http\Controllers\API\OrderController');
        Route::resource('/order-items','App\Http\Controllers\API\OrderItemController');
        Route::resource('/payments','App\Http\Controllers\API\PaymentController');
        Route::resource('/products','App\Http\Controllers\API\ProductController');
        Route::resource('/reviews','App\Http\Controllers\API\ReviewController');
        Route::resource('/shippings','App\Http\Controllers\API\ShippingController');
        Route::resource('/shops','App\Http\Controllers\API\ShopController');
        // Route::resource('/users','App\Http\Controllers\API\UserController');
        // Route::resource('/users','App\Http\Controllers\API\UserController');
        // Route::resource('/users','App\Http\Controllers\API\UserController');
        // Route::resource('/users','App\Http\Controllers\API\UserController');
        // Route::resource('/users','App\Http\Controllers\API\UserController');
        // Route::resource('/users','App\Http\Controllers\API\UserController');
        // Route::resource('/users','App\Http\Controllers\API\UserController');
    });

    // Routes accessible to sellers
    Route::middleware('role:seller')->group(function () {
        // Seller-only routes
    });

    // Routes accessible to customers
    Route::middleware('role:customer')->group(function () {
        // Customer-only routes
    });
});
