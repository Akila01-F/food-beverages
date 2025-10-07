<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/login-username', [AuthController::class, 'loginWithUsername']);
    
    // Categories
    Route::get('categories', [CategoryApiController::class, 'index']);
    Route::get('categories/{category}', [CategoryApiController::class, 'show']);
    
    // Products
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/featured', [ProductApiController::class, 'getFeatured']);
    Route::get('products/search', [ProductApiController::class, 'search']);
    Route::get('products/{product}', [ProductApiController::class, 'show']);
    Route::get('categories/{category}/products', [ProductApiController::class, 'getByCategory']);
});

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        // Authentication routes (protected)
        Route::get('auth/profile', [AuthController::class, 'profile']);
        Route::put('auth/profile', [AuthController::class, 'updateProfile']);
        Route::post('auth/change-password', [AuthController::class, 'changePassword']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);
        
        // User info (legacy endpoint)
        Route::get('user', function (Request $request) {
            return response()->json([
                'status' => 'success',
                'message' => 'User retrieved successfully',
                'data' => [
                    'user' => [
                        'id' => $request->user()->id,
                        'name' => $request->user()->name,
                        'username' => $request->user()->username,
                        'email' => $request->user()->email,
                        'is_admin' => $request->user()->is_admin,
                    ]
                ]
            ]);
        });
        
        // Orders
        Route::get('orders', [OrderApiController::class, 'index']);
        Route::post('orders', [OrderApiController::class, 'store']);
        Route::get('orders/{order}', [OrderApiController::class, 'show']);
        Route::put('orders/{order}', [OrderApiController::class, 'update']);
    });
});
