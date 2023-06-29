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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Auth::routes();


Route::resource('users','App\Http\Controllers\UserController');
Route::resource('customers','App\Http\Controllers\CustomerController');
Route::resource('shops','App\Http\Controllers\ShopController');
Route::resource('products','App\Http\Controllers\ProductController');
Route::resource('discounts','App\Http\Controllers\DiscountController');
Route::resource('categorys','App\Http\Controllers\CategoryController');
Route::resource('getcategory','App\Http\Controllers\GetCategoryProduct');
Route::resource('getshoppro','App\Http\Controllers\GetShopProductController');



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('')->middleware(['auth','isAdmin'])->group(function(){
    
    Route::get('/hello', [App\Http\Controllers\HomeController::class, 'index'])->name('hello');

});



