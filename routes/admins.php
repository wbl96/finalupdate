<?php

use App\Http\Controllers\Admins\{
    AboutUsController,
    AdminController,
    CategoriesController,
    DashboardController,
    FaqsController,
    OrdersController,
    ServicesController,
    SettingsController,
    TermsAndConditionsController,
    UseAndPrivacyController,
    UserController,
    ProductController
};
use App\Http\Controllers\Admins\Auth\AdminLoginController;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'admin.auth'])->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('showlogin');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login');
    // Route::get('password/{token}', [ForgotPasswordController::class, 'showResetView'])->name('sendResetPassowrd');
    // Route::post('password', [ForgotPasswordController::class, 'reset'])->name('setPassowrd');
});

Route::middleware(['auth:admin'])->group(function () {
    // truncate Cart table
    Route::get('/truncate/cart', function () {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Cart::truncate();
        Order::truncate();
        // DB::table('cart_items')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        dd(Cart::with('items.detail.product')->get());
    });
    // migration route
    Route::get('migrate', function () {
        Artisan::call('migrate');
        // return to dashboard
        return redirect()->route('admin.dashboard');
    });
    Route::get('/migrate/rollback/{steps}', function ($steps) {
        Artisan::call('migrate:rollback', ['--step' => $steps]);
        // return to dashboard
        return redirect()->route('admin.dashboard');
    });
    // seed route
    Route::get('seed', function () {
        Artisan::call('db:seed');
        // return to dashboard
        return redirect()->route('admin.dashboard');
    });
    // update composer
    Route::get('/composer-update', function () {
        $output = [];
        $returnCode = 0;
        exec('composer update 2>&1', $output, $returnCode);
        // return result
        return response()->json([
            'output' => $output,
            'status' => $returnCode === 0 ? 'success' : 'error'
        ]);
    });
    // admin dashboard
    Route::post('updateFCM', [DashboardController::class, 'updateFCM'])->name('updateFCM');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // admins routes
    Route::prefix('admins')->controller(AdminController::class)->name('admins.')->group(function () {
        Route::get('/get-admins', 'getAdmins')->name('getAdmins');
        Route::resource('/', AdminController::class)->names([
            'index' => 'list',
            'store' => 'store',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy',
        ])->parameter('', 'admin');
        Route::post('/sendRestPassword/{email}', 'sendResetPasswordEmail')->name('sendRestPassword');
        Route::get('/{id}/show-permissions-modal', 'showPermission')->name('showPermissionsModal');
        Route::put('/{id}/update-permissions', 'updatePermissions')->name('updatePermissions');
        Route::get('/permissions', 'roleShow')->name('role.show');
        Route::post('/permissions', 'roleStore')->name('role.store');
    });

    // users routes
    Route::prefix('admin')->group(function () {
        Route::prefix('users')->group(function () {
            // مسار عرض المتاجر
            Route::get('store', [UserController::class, 'showTypes']);
            
            // مسارات المتاجر
            Route::prefix('stores')->group(function () {
                Route::get('{store}/edit', [UserController::class, 'editStore']);
                Route::put('{store}/update', [UserController::class, 'updateStore']);
                Route::delete('{store}', [UserController::class, 'destroy']);
                Route::post('{store}/mark-as-paid', [UserController::class, 'markTransactionsAsPaid']);
            });
            
            // مسارات المستخدمين العامة
            Route::resource('/', UserController::class)
                ->except(['show', 'create'])
                ->parameter('', 'user');
        });
    });

    // services routes
    Route::prefix('services')->controller(ServicesController::class)->name('services.')->group(function () {
        Route::get('/get-services', 'getServices')->name('getServices');
        Route::resource('/', ServicesController::class)
            ->except(['show', 'create'])
            ->parameter('', 'service');
    });

    // categories routes
    Route::prefix('categories')->controller(CategoriesController::class)->name('categories.')->group(function () {
        Route::get('/get-categories', 'getCategories')->name('getCategories');
        Route::resource('/', CategoriesController::class)
            ->except(['show', 'create'])
            ->parameter('', 'category');
    });

    // get subcategories
    Route::get('/categories/{category}/get-subcategories', [CategoriesController::class, 'getSubcategories'])->name('categories.getSubcategories');

    // site content
    Route::prefix('site-content')->name('site-content.')->group(function () {
        // terms & conditions
        Route::resource('/terms-conditions', TermsAndConditionsController::class)
            ->only(['index', 'update'])
            ->names([
                'index' => 'terms.index',
                'update' => 'terms.update',
            ])->parameter('terms-conditions', 'terms');
        // use & privacy policy
        Route::resource('/privacy-policy', UseAndPrivacyController::class)
            ->only(['index', 'update'])
            ->names([
                'index' => 'privacy.index',
                'update' => 'privacy.update',
            ])->parameter('privacy-policy', 'privacy');
        // about us
        Route::resource('/about-us', AboutUsController::class)
            ->only(['index', 'update'])
            ->names([
                'index' => 'about.index',
                'update' => 'about.update',
            ])->parameter('about-us', 'about');
        // faqs
        Route::get('/faqs/get-faqs', [FaqsController::class, 'getFaqs'])->name('faq.getFaqs');
        Route::resource('/faqs', FaqsController::class)
            ->only(['index', 'store', 'edit', 'update', 'destroy'])
            ->names([
                'index' => 'faq.index',
                'store' => 'faq.store',
                'edit' => 'faq.edit',
                'update' => 'faq.update',
                'destroy' => 'faq.destroy',
            ])->parameter('faqs', 'faq');
    });

    // settings
    Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
        Route::get('/general-settings', 'generalSettings')->name('generalSettings');
        Route::put('/general-settings', 'updateGeneralSettings')->name('updateGeneralSettings');
        Route::get('/contacts-settings', 'contactsSettings')->name('contactsSettings');
        Route::put('/contacts-settings', 'updateContactsSettings')->name('updateContactsSettings');
    });

    // orders
    Route::prefix('orders')->name('orders.')->controller(OrdersController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/order_items_ajax/{id}', 'getOrderItems');
        Route::get('/get-orders', 'getOrders')->name('get-orders');
        // Route::get('/{order}/details', 'show')->name('show');
        Route::post('/approve', 'approve')->name('approve');
    });

    // products
    Route::resource('products', ProductController::class);
    Route::post('products/details/{id}', [ProductController::class, 'destroyProductDetailKey'])->name('products.deleteDetailKey');
    Route::get('products/requests/new', [ProductController::class, 'newRequests'])->name('products.requests');
    Route::post('products/requests/{productSupplier}/activate', [ProductController::class, 'activateRequest'])->name('products.activate');
    Route::post('products/requests/{productSupplier}/deactivate', [ProductController::class, 'deactivateRequest'])->name('products.deactivate');
});

// للتأكد من أن المسارات تعمل
Route::get('/test-routes', function() {
    dd(Route::getRoutes()->getRoutesByName());
});
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    // مسار تسديد المبالغ
    Route::post('/stores/{store}/mark-as-paid', [UserController::class, 'markTransactionsAsPaid'])
        ->name('admin.stores.mark-as-paid');

    // users routes
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/{type}', [UserController::class, 'showTypes'])->name('list');
        Route::get('/stores/{store}/edit', [UserController::class, 'editStore'])->name('stores.edit');
        Route::put('/stores/{store}/update', [UserController::class, 'updateStore'])->name('stores.update');
        Route::delete('/stores/{store}', [UserController::class, 'destroy'])->name('stores.destroy');
        
        Route::resource('/', UserController::class)
            ->except(['show', 'create'])
            ->parameter('', 'user');
    });
});

