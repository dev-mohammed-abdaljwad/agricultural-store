<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductImageController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Api\V1\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\V1\Admin\OrderController as AdminOrderController;

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'registerCustomer']);
        Route::post('/login', [AuthController::class, 'login']);

        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });

    // Public product routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);

        // Product image routes (public - view only)
        Route::get('/{product}/images', [ProductImageController::class, 'index']);
        Route::get('/{product}/images/primary', [ProductImageController::class, 'primary']);
        Route::get('/{product}/images/{image}', [ProductImageController::class, 'show']);
    });

    // Public category routes
    Route::get('/categories', [CategoryController::class, 'index']);

    // Admin routes
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        // Admin product routes
        Route::prefix('admin/products')->group(function () {
            Route::get('/', [AdminProductController::class, 'index']);
            Route::post('/', [AdminProductController::class, 'store']);
            Route::put('/{product}', [AdminProductController::class, 'update']);
            Route::delete('/{product}', [AdminProductController::class, 'destroy']);
            Route::post('/{product}/images', [AdminProductController::class, 'addImages']);
            Route::put('/{product}/specs', [AdminProductController::class, 'syncSpecs']);

            // Product image management routes
            Route::prefix('/{product}/images')->group(function () {
                Route::post('/', [ProductImageController::class, 'store']);
                Route::put('/{image}', [ProductImageController::class, 'update']);
                Route::delete('/{image}', [ProductImageController::class, 'destroy']);
                Route::post('/reorder', [ProductImageController::class, 'reorder']);
            });
        });

        // Admin order routes
        Route::prefix('admin/orders')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index']);
            Route::get('/{order}', [AdminOrderController::class, 'show']);
            Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus']);
            Route::post('/{order}/quotes', [AdminOrderController::class, 'sendQuote']);
            Route::get('/{order}/tracking', [AdminOrderController::class, 'getTracking']);
        });

        // Admin conversations
        Route::get('/admin/conversations', [ChatController::class, 'getConversations']);
    });

    // Customer order routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // Customer order routes
        Route::prefix('orders')->group(function () {
            Route::get('/', [CustomerOrderController::class, 'index']);
            Route::post('/', [CustomerOrderController::class, 'store']);
            Route::get('/{order}', [CustomerOrderController::class, 'show']);
            Route::post('/{order}/quotes/{quote}/accept', [CustomerOrderController::class, 'acceptQuote']);
            Route::post('/{order}/quotes/{quote}/reject', [CustomerOrderController::class, 'rejectQuote']);
        });

        // Chat routes
        Route::prefix('orders/{order}/messages')->group(function () {
            Route::get('/', [ChatController::class, 'getMessages']);
            Route::post('/', [ChatController::class, 'sendMessage']);
        });
    });
});
