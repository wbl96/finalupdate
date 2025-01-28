<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Store\ProductsCategoriesController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// require __DIR__ . '/suppliers.php';
// require __DIR__ . '/store.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
    Route::get('/store/wallet', [App\Http\Controllers\Store\WalletController::class, 'index'])->name('store.wallet.index');
});

Route::prefix('store')->middleware(['auth', 'verified'])->name('store.')->group(function () {
    Route::get('/wallet', [App\Http\Controllers\Store\WalletController::class, 'index'])->name('wallet.index');
});

Route::get('/', function (Request $request) {
    // get search value
    $search = $request->input('search');
    // get suppliers products
    $products = Product::when($search, function ($query, $search) {
            return $query->where('name_ar', 'like', "%{$search}%")
                ->orWhere('name_ar', 'like', "%{$search}%");
        })
        ->with('detail', function($q){
            $q->with('keys', function($q){
                $q->select([
                    'id',
                    'products_detail_id',
                    'key',
                ]);
            })
            ->select([
                'products_details.id',
                'product_id',
                'type',
                'name',
            ]);
        })
        ->where('status', 'active')
        ->orderByDesc('id')
        ->paginate(12);

    return view('welcome', compact('products'));
})->middleware('store.auth')->name('welcome');

Route::get('/category/{category}/products', [ProductsCategoriesController::class, 'show'])->name('guest.categories.get-products');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
