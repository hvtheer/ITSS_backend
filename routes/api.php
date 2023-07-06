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
    Route::middleware('role_id:1')->prefix('v1')->group(function () {
        Route::resource('/users','App\Http\Controllers\API\UserController');
        Route::resource('/categories','App\Http\Controllers\API\CategoryController');
        Route::resource('/coupons','App\Http\Controllers\API\CouponController');
        Route::resource('/user-coupons','App\Http\Controllers\API\UserCouponController');
        Route::resource('/customers','App\Http\Controllers\API\CustomerController');
        Route::resource('/invoices','App\Http\Controllers\API\InvoiceController');
        Route::resource('/orders','App\Http\Controllers\API\OrderController');
        Route::resource('/order-items','App\Http\Controllers\API\OrderItemController');
        Route::resource('/payment-tranacstions','App\Http\Controllers\API\PaymentTransactionController');
        Route::resource('/products','App\Http\Controllers\API\ProductController');
        Route::resource('/product-coupons','App\Http\Controllers\API\ProductCouponController');
        Route::resource('/product-images','App\Http\Controllers\API\ProductImageController');
        Route::resource('/product-attributes','App\Http\Controllers\API\ProductAttributeController');
        Route::resource('/reviews','App\Http\Controllers\API\ReviewController');
        Route::resource('/roles','App\Http\Controllers\API\RoleController');
        Route::resource('/role-users','App\Http\Controllers\API\RoleUserController');
        Route::resource('/delivery-info','App\Http\Controllers\API\DeliveryInfoController');
        Route::resource('/shops','App\Http\Controllers\API\ShopController');
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
