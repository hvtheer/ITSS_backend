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

// Route::resource('/v1/products', 'App\Http\Controllers\API\ProductController');

//　Routes accessible to authentication
Route::post('/v1/register', 'App\Http\Controllers\Auth\RegisterController@register');
Route::post('/v1/login', 'App\Http\Controllers\Auth\LoginController@login');

Route::post('create-post', 'App\Http\Controllers\PaymentController@createPost');

Route::prefix('v1')->group(function () {
    Route::get('/products', 'App\Http\Controllers\API\ProductController@index');
    Route::get('/products/best-sellers', 'App\Http\Controllers\API\ProductController@getBestSellingProducts');
    Route::get('/shops/top', 'App\Http\Controllers\API\ShopController@topShopsBySoldQuantity');
    Route::get('/shops/{shop}', 'App\Http\Controllers\API\ShopController@show');
    Route::get('/products/latest', 'App\Http\Controllers\API\ProductController@getLatestProducts');
    Route::resource('/categories', 'App\Http\Controllers\API\CategoryController')->only(['show','index']);
    Route::resource('/shops', 'App\Http\Controllers\API\ShopController')->only(['show','index']);
    Route::get('/products/{product}', 'App\Http\Controllers\API\ProductController@show');
    
    // Routes accessible to all authenticated users
    Route::middleware('auth:api')->group(function () {
    
        Route::get('/current', 'App\Http\Controllers\API\UserController@getCurrent');
        Route::resource('/users', 'App\Http\Controllers\API\UserController');
        Route::resource('/categories', 'App\Http\Controllers\API\CategoryController')->except(['index', 'show']);
        Route::resource('/coupons', 'App\Http\Controllers\API\CouponController');
        Route::resource('/customers', 'App\Http\Controllers\API\CustomerController');
        Route::resource('/invoices', 'App\Http\Controllers\API\InvoiceController');
        Route::resource('/orders', 'App\Http\Controllers\API\OrderController');
        Route::resource('/payment-transactions', 'App\Http\Controllers\API\PaymentTransactionController');
        Route::resource('/products', 'App\Http\Controllers\API\ProductController')->except(['index', 'show']);
        Route::resource('/reviews', 'App\Http\Controllers\API\ReviewController');
        Route::resource('/roles', 'App\Http\Controllers\API\RoleController');
        Route::resource('/delivery-info', 'App\Http\Controllers\API\DeliveryInfoController');
        Route::resource('/shops', 'App\Http\Controllers\API\ShopController')->except(['index', 'show']);
        
    });
    
});