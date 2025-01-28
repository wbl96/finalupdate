<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Client;
use App\Models\Faq;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductsCategory;
use App\Models\ProductsSubCategories;
use App\Models\Service;
use App\Models\SuppliersPolicy;
use App\Observers\CartItemsObserver;
use App\Observers\CartsObserver;
use App\Observers\ClientsObserver;
use App\Observers\FaqsObserver;
use App\Observers\InvoicesObserver;
use App\Observers\OrderObserver;
use App\Observers\PoliciesObserver;
use App\Observers\ProductsCategoriesObserver;
use App\Observers\ProductsObserver;
use App\Observers\ProductsSubCategoriesObserver;
use App\Observers\ServicesObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        $this->observers();
    }

    public function observers(): void
    {
        Cart::observe(CartsObserver::class);
        CartItem::observe(CartItemsObserver::class);
        Client::observe(ClientsObserver::class);
        Faq::observe(FaqsObserver::class);
        Invoice::observe(InvoicesObserver::class);
        Order::observe(OrderObserver::class);
        Product::observe(ProductsObserver::class);
        ProductsCategory::observe(ProductsCategoriesObserver::class);
        ProductsSubCategories::observe(ProductsSubCategoriesObserver::class);
        SuppliersPolicy::observe(PoliciesObserver::class);
        Service::observe(ServicesObserver::class);
    }
}
