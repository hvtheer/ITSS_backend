<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/aaa', function () {
    echo "Hello";
});

Auth::routes();

Route::resource('user','App\Http\Controllers\UserController');
Route::resource('customer','App\Http\Controllers\CustomerController');
Route::resource('shop','App\Http\Controllers\ShopController');
Route::resource('product','App\Http\Controllers\ProductController');
Route::resource('discount','App\Http\Controllers\DiscountController');
Route::resource('category','App\Http\Controllers\CategoryController');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('')->middleware(['auth','isAdmin'])->group(function(){
    
    Route::get('/hello', [App\Http\Controllers\HomeController::class, 'index'])->name('hello');

});





