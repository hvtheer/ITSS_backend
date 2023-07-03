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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('/user','App\Http\Controllers\API\UserController');
Route::resource('/customer','App\Http\Controllers\API\CustomerController');
Route::resource('/seller','App\Http\Controllers\API\SellerController');
Route::resource('/category','App\Http\Controllers\API\CategoryController');
Route::resource('/order','App\Http\Controllers\API\OrdersController');

