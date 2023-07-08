<?php

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function Ramsey\Uuid\v1;

/*
|--------------------------------------------------------------------------
| API\ Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API\ routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//　Routes accessible to authentication
Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');

Route::prefix('v1')->group(function () {
    Route::get('/products/filter', 'App\Http\Controllers\API\ProductController@filterProducts');
    Route::get('/products/best-sellers', 'App\Http\Controllers\API\ProductController@getBestSellingProducts');
    Route::get('/shops', 'App\Http\Controllers\API\ShopController@index');
    Route::get('/shops/top', 'App\Http\Controllers\API\ShopController@topShopsBySoldQuantity');
    Route::get('/shops/{shop}', 'App\Http\Controllers\API\ShopController@show');
    Route::get('/products/latest', 'App\Http\Controllers\API\ProductController@getLatestProducts');
    Route::get('/categories', 'App\Http\Controllers\API\CategoryController@index');
    Route::get('/products', 'App\Http\Controllers\API\ProductController@index');
    
    // Routes accessible to all authenticated users
    Route::middleware('auth:api')->group(function () {
    
        // Route::get('/current', 'App\Http\Controllers\API\UserController@getCurrent');
        // Route::resource('/users', 'App\Http\Controllers\API\UserController');
        // Route::resource('/categories', 'App\Http\Controllers\API\CategoryController');
        // Route::resource('/coupons', 'App\Http\Controllers\API\CouponController');
        // Route::resource('/customers', 'App\Http\Controllers\API\CustomerController');
        Route::resource('/invoices', 'App\Http\Controllers\API\InvoiceController');
        Route::resource('/orders', 'App\Http\Controllers\API\OrderController');
        Route::resource('/payment-transactions', 'App\Http\Controllers\API\PaymentTransactionController');
        // Route::resource('/products', 'App\Http\Controllers\API\ProductController');
        Route::resource('/reviews', 'App\Http\Controllers\API\ReviewController');
        // Route::resource('/roles', 'App\Http\Controllers\API\RoleController');
        Route::resource('/delivery-info', 'App\Http\Controllers\API\DeliveryInfoController');
        // Route::resource('/shops', 'App\Http\Controllers\API\ShopController');
    
        // // Routes accessible to admins
        // Route::middleware('role:' . Role::ROLE_ADMIN)->group(function () {
        //     Route::resource('/users', 'App\Http\Controllers\API\UserController');
        //     Route::resource('/categories', 'App\Http\Controllers\API\CategoryController')->only('store', 'update', 'destroy');
        //     Route::resource('/coupons', 'App\Http\Controllers\API\CouponController');
        //     Route::resource('/customers', 'App\Http\Controllers\API\CustomerController');
        //     Route::resource('/invoices', 'App\Http\Controllers\API\InvoiceController');
        //     Route::resource('/orders', 'App\Http\Controllers\API\OrderController');
        //     Route::resource('/payment-transactions', 'App\Http\Controllers\API\PaymentTransactionController');
        //     Route::resource('/reviews', 'App\Http\Controllers\API\ReviewController');
        //     Route::resource('/roles', 'App\Http\Controllers\API\RoleController');
        //     Route::resource('/delivery-info', 'App\Http\Controllers\API\DeliveryInfoController');
        //     Route::resource('/shops', 'App\Http\Controllers\API\ShopController');
        // });
    
        // Routes accessible to sellers
        // Route::middleware('role:' . Role::ROLE_SELLER)->group(function () {
        //     Route::resource('/users', 'App\Http\Controllers\API\UserController');
        //     Route::resource('/categories', 'App\Http\Controllers\API\CategoryController');
        //     Route::resource('/coupons', 'App\Http\Controllers\API\CouponController');
        //     Route::resource('/customers', 'App\Http\Controllers\API\CustomerController');
        //     Route::resource('/invoices', 'App\Http\Controllers\API\InvoiceController');
        //     Route::resource('/orders', 'App\Http\Controllers\API\OrderController');
        //     Route::resource('/payment-transactions', 'App\Http\Controllers\API\PaymentTransactionController');
        //     Route::resource('/products', 'App\Http\Controllers\API\ProductController');
        //     Route::resource('/reviews', 'App\Http\Controllers\API\ReviewController');
        //     Route::resource('/roles', 'App\Http\Controllers\API\RoleController');
        //     Route::resource('/delivery-info', 'App\Http\Controllers\API\DeliveryInfoController');
        //     Route::resource('/shops', 'App\Http\Controllers\API\ShopController');
        // });
    
        // Routes accessible to customers
        // Route::middleware('role:'. Role::ROLE_CUSTOMER)->group(function () {

        // });
    });
    
});