<?php

use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductGalleryController;
use App\Http\Controllers\API\ProductUnitController;
use App\Http\Controllers\API\SalesTransactionsController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('products', [ProductController::class, 'all']);
Route::get('categories', [ProductCategoryController::class, 'all']);
Route::get('units', [ProductUnitController::class, 'all']);
Route::get('customers', [CustomerController::class, 'all']);

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);

    Route::post('categories/store', [ProductCategoryController::class, 'store']);

    Route::post('units/store', [ProductUnitController::class, 'store']);
    
    Route::post('products/store', [ProductController::class, 'store']);
    Route::post('products/update/{id}', [ProductController::class, 'update']);
    
    Route::post('customers/store', [CustomerController::class, 'store']);

    Route::post('gallery/store', [ProductGalleryController::class, 'store']);

    Route::get('sales',[SalesTransactionsController::class, 'all']);
    Route::post('sales-transaction',[SalesTransactionsController::class, 'transaction']);
});