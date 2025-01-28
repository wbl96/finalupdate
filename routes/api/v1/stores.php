<?php

// Stores routes

use App\Http\Controllers\Api\V1\Stores\AuthController;
use App\Http\Controllers\Api\V1\Stores\CartController;
use App\Http\Controllers\Api\V1\Stores\OrdersController;
use App\Http\Controllers\Api\V1\Stores\ProductsCategoriesController;
use App\Http\Controllers\Api\V1\Stores\ProductsController;
use App\Http\Controllers\Api\V1\Stores\ReportsController;
use App\Http\Controllers\Api\V1\Stores\RFQController;
use App\Http\Controllers\Api\V1\Stores\SuppliersController;
use Illuminate\Support\Facades\Route;

Route::prefix('stores')->name('storesApi.')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        // logout route
        Route::post('/logout', [AuthController::class, 'logout']);
        // Store routes
        Route::get('/products', [ProductsController::class, 'index']);
        Route::get('/category/{category_id}/products', [ProductsController::class, 'getCategoriesProducts']);
        // products categories
        Route::apiResource('/categories', ProductsCategoriesController::class)->only(['index', 'show']);
        // suppliers rourts
        Route::apiResource('/suppliers', SuppliersController::class)->only(['index', 'store']);
        // cart rourts
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/add', [CartController::class, 'store']);
        Route::delete('/cart/{product}/remove', [CartController::class, 'destroy']);

        // orders routes
        Route::apiResource('orders', OrdersController::class)->only(['index', 'show']);
        Route::post('/orders/confirm', [OrdersController::class, 'confirm'])->name('orders.confirm');
        Route::post('/orders/request-for-quotations', [OrdersController::class, 'requestForQuotations'])->name('orders.request-for-quotations');
        Route::post('/orders/{order}/upload-receipt', [OrdersController::class, 'uploadReceipt'])->name('orders.upload-receipt');
        
        // rfq routes
        Route::get('/rfq', [RFQController::class, 'index']);
        Route::post('/rfq', [RFQController::class, 'store']);
        Route::post('/rfq/receipt', [RFQController::class, 'uploadReceipt']);

        // reports
        Route::prefix('reports')->controller(ReportsController::class)->group(function () {
            Route::get('/purchases', 'purchasesReports');
            Route::get('/payments', 'paymentsReports');
        });
    });
    // login 
    Route::post('/login', [AuthController::class, 'login']);
});
