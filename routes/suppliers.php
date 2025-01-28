<?php

use App\Http\Controllers\Suppliers\ProductsCategoriesController;
use App\Http\Controllers\Suppliers\Auth\LoginController;
use App\Http\Controllers\Suppliers\ClientsController;
use App\Http\Controllers\Suppliers\DashboardController;
use App\Http\Controllers\Suppliers\OrdersController;
use App\Http\Controllers\Suppliers\PoliciesController;
use App\Http\Controllers\Suppliers\ProductsController;
use App\Http\Controllers\Suppliers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    // Route::get('password/{token}', [ForgotPasswordController::class, 'showResetView'])->name('sendResetPassowrd');
    // Route::post('password', [ForgotPasswordController::class, 'reset'])->name('setPassowrd');
});


Route::middleware(['auth:supplier'])->group(function () {
    // dashboard
    Route::post('updateFCM', [DashboardController::class, 'updateFCM'])->name('updateFCM');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // clients routes
    Route::prefix('clients')->controller(ClientsController::class)->name('clients.')->group(function () {
        Route::get('/get-clients', 'getClients')->name('getClients');
        Route::resource('/', ClientsController::class)->name('index', 'list')->only(['index', 'create', 'store'])->parameter('', 'client');
    });

    // products routes
    Route::prefix('products')->controller(ProductsController::class)->name('products.')->group(function () {
        Route::get('/get-products', 'getProducts')->name('getProducts');
        Route::post('/ajax-get-products', 'ajaxGetProducts')->name('ajaxGetProducts');
        Route::resource('/', ProductsController::class)->names([
            'index' => 'list',
            'create' => 'create',
            'store' => 'store',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy',
        ])->except(['show'])->parameter('', 'product');
    });

    // get subcategories
    Route::get('/categories/{category}/get-subcategories', [ProductsCategoriesController::class, 'getSubcategories'])->name('categories.getSubcategories');

    // sale and return policies route
    Route::resource('/sales-return-policies', PoliciesController::class)
        ->parameter('sales-return-policies', 'policy')
        ->only(['index', 'store', 'update', 'destroy'])
        ->names([
            'index' => 'policies.index',
            'store' => 'policies.store',
            'update' => 'policies.update',
            'destroy' => 'policies.destroy',
        ]);

    // orders routes
    Route::prefix('orders')->name('orders.')->controller(OrdersController::class)->group(function () {
        Route::get('', 'index')->name('list');
        Route::get('/get-orders', 'getOrders')->name('get-orders');
        Route::get('/{order}/details', 'show')->name('show');
        // Route::put('/{order}/update-status', 'updateStatus')->name('update-status');
        // Route::post('/{order}/add-payment', 'addPayment')->name('add-payment');
        Route::get('/rfq-requests', 'getRfqRequests')->name('rfq-requests');
        // Route::get('/{cart}/submit-quote', 'submitQuoteView')->name('submit-quote');
        Route::post('/submit-quote', 'submitQuote')->name('submit-quote');
    });

    // reports routes
    Route::prefix('reports')->name('reports.')->controller(ReportsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/products', 'productsReports')->name('products');
        Route::get('/sales', 'salesReports')->name('sales');
        Route::get('/payments', 'paymentsReports')->name('payments');
    });
});
