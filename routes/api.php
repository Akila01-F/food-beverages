<?php

use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API routes
Route::prefix('v1')->group(function () {
    // Products
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{product}', [ProductApiController::class, 'show']);
    Route::get('categories/{category}/products', [ProductApiController::class, 'getByCategory']);
});

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::prefix('v1')->group(function () {
        // Orders
        Route::get('orders', [OrderApiController::class, 'index']);
        Route::post('orders', [OrderApiController::class, 'store']);
        Route::get('orders/{order}', [OrderApiController::class, 'show']);
        Route::put('orders/{order}', [OrderApiController::class, 'update']);
    });
});
