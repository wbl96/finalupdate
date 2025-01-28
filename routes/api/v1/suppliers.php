<?php

// Suppliers routes
use App\Http\Controllers\Api\V1\Suppliers\AuthController;
use App\Http\Controllers\Api\V1\Suppliers\ClientsController;
use App\Http\Controllers\Api\V1\Suppliers\OrdersController;
use App\Http\Controllers\Api\V1\Suppliers\PoliciesController;
use App\Http\Controllers\Api\V1\Suppliers\ProductsCategoriesController;
use App\Http\Controllers\Api\V1\Suppliers\ProductsController;
use App\Http\Controllers\Api\V1\Suppliers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::prefix('suppliers')->name('suppliersApi.')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        // logout route
        Route::post('/logout', [AuthController::class, 'logout']);
        // clients (stores) routes
        Route::apiResource('/clients', ClientsController::class);
        // products routes
        Route::apiResource('/products', ProductsController::class);
        // products categories
        Route::apiResource('/categories', ProductsCategoriesController::class)->only(['index', 'show']);
        // orders routes
        Route::apiResource('/orders', OrdersController::class)->except(['store']);
        Route::post('/orders/{order}/add-payment', [OrdersController::class, 'addPayment']);
        Route::get('/orders/rfq/requests', [OrdersController::class, 'getRfqRequests']);
        Route::post('/orders/submit-quote', [OrdersController::class, 'submitQuote']);

        // policies routes
        Route::get('/sales-return-policies', [PoliciesController::class, 'show']);
        Route::put('/sales-return-policies', [PoliciesController::class, 'update']);
        // reports
        Route::prefix('reports')->controller(ReportsController::class)->group(function () {
            Route::get('/stores', 'storesReports');
            Route::get('/sales', 'salesReports');
            Route::get('/payments', 'paymentsReports');
        });
    });
    // login
    Route::post('/login', [AuthController::class, 'login']);
});
