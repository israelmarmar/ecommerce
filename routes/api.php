<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/product', [App\Http\Controllers\ProductController::class, 'index']);
Route::get('/allproduct', [App\Http\Controllers\ProductController::class, 'showAll']);
Route::post('/product/update/{id}', [App\Http\Controllers\ProductController::class, 'update']);
Route::post('/product/add', [App\Http\Controllers\ProductController::class, 'create']);
Route::get('/purchase', [App\Http\Controllers\PurchaseController::class, 'index']);
Route::post('/purchase/add', [App\Http\Controllers\PaymentController::class, 'checkout']);
Route::get('/cart', [App\Http\Controllers\CartController::class, 'showProducts']);
Route::get('/orders', [App\Http\Controllers\CartController::class, 'showOrders']);
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addProductToCart']);
Route::post('/cart/delete/{id}', [App\Http\Controllers\CartController::class, 'destroyItem']);
Route::get('/cart/increment/{id}', [App\Http\Controllers\CartController::class, 'incrementProduct']);
Route::get('/cart/decrement/{id}', [App\Http\Controllers\CartController::class, 'decrementProduct']);
Route::post('/product/delete/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
