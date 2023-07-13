<?php

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

// Route::resource('/v1/products', 'ProductController');

//　Routes accessible to authentication
Route::post('/v1/register-customer', 'App\Http\Controllers\Auth\RegisterController@registerCustomer');
Route::post('/v1/register-shop', 'App\Http\Controllers\Auth\RegisterController@registerShop');
Route::post('/v1/login', 'App\Http\Controllers\Auth\LoginController@login');

Route::prefix('v1')->namespace('App\Http\Controllers\API')->group(function () {
    Route::get('/products/best-sellers', 'ProductController@getBestSellingProducts');
    Route::get('/shops/top', 'ShopController@topShopsBySoldQuantity');
    Route::get('/shops/{shop}', 'ShopController@show');
    Route::get('/products/latest', 'ProductController@getLatestProducts');
    Route::resource('/categories', 'CategoryController')->only(['show','index']);
    Route::resource('/shops', 'ShopController')->only(['show','index']);
    Route::resource('/products', 'ProductController')->only(['show','index']);
    
    // Routes accessible to all authenticated users
    Route::middleware('auth:api')->group(function () {
    
        Route::get('/current', 'UserController@getCurrent');
        Route::resource('/users', 'UserController');
        Route::resource('/categories', 'CategoryController')->except(['index', 'show']);
        Route::resource('/coupons', 'CouponController');
        Route::resource('/customers', 'CustomerController');
        Route::resource('/invoices', 'InvoiceController');
        Route::resource('/orders', 'OrderController');
        Route::resource('/payment-transactions', 'PaymentTransactionController');
        Route::resource('/products', 'ProductController')->except(['index', 'show']);
        Route::resource('/reviews', 'ReviewController');
        Route::resource('/roles', 'RoleController');
        Route::resource('/delivery-info', 'DeliveryInfoController');
        Route::resource('/shops', 'ShopController')->except(['index', 'show']);
        
    });
    
});