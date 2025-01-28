<?php

use App\Http\Controllers\Store\Auth\LoginController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\DashboardController;
use App\Http\Controllers\Store\OrdersController;
use App\Http\Controllers\Store\ProductsCategoriesController;
use App\Http\Controllers\Store\ProductsController;
use App\Http\Controllers\Store\ReportsController;
use App\Http\Controllers\Store\RFQController;
use App\Http\Controllers\Store\SuppliersController;
use App\Http\Controllers\Store\WalletController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'store.auth'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    // Route::get('password/{token}', [ForgotPasswordController::class, 'showResetView'])->name('sendResetPassowrd');
    // Route::post('password', [ForgotPasswordController::class, 'reset'])->name('setPassowrd');
});


Route::middleware(['auth:store'])->group(function () {
    // dashboard
    Route::post('updateFCM', [DashboardController::class, 'updateFCM'])->name('updateFCM');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // get product modal
    Route::get('/product/{product}', [ProductsController::class, 'show'])->name('products.get-product');

    // get products of gived category
    Route::get('/category/{category}/products', [ProductsCategoriesController::class, 'show'])->name('categories.get-products');

    // carts routes
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::post('/cart/{product}/add', [CartController::class, 'store'])->name('cart.add-item');
    Route::delete('/cart/{id}/remove', [CartController::class, 'remove'])->name('cart.remove-item');

    // orders route
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/get-orders', [OrdersController::class, 'getOrders'])->name('orders.get-orders');
    Route::get('/orders/{order}/details', [OrdersController::class, 'show'])->name('orders.show');
    // Route::post('/orders/confirm', [OrdersController::class, 'confirm'])->name('orders.confirm');
    Route::post('/orders/request-for-quotations', [OrdersController::class, 'requestForQuotations'])->name('orders.request-for-quotations');
    Route::post('/orders/{order}/upload-receipt', [OrdersController::class, 'uploadReceipt'])->name('orders.upload-receipt');

    //RFQ
    Route::get('/rfq', [RFQController::class, 'index'])->name('rfq.index');
    Route::post('/rfq', [RFQController::class, 'store'])->name('rfq.store');

    // suppliers routes
    Route::get('/{type}', [SuppliersController::class, 'getSuppliers'])->name('suppliers.get');
    Route::get('/suppliers/list', [SuppliersController::class, 'index'])->name('suppliers.list');
    Route::post('/suppliers/store', [SuppliersController::class, 'store'])->name('suppliers.store');

    // wallet route
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');

    // reports routes
    Route::prefix('reports/t/')->name('reports.')->controller(ReportsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/purchases', 'purchasesReports')->name('purchases');
        Route::get('/payments', 'paymentsReports')->name('payments');
    });
});
