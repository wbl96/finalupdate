<?php

namespace App\Providers;

use App\Models\ProductsCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('locale', app()->getLocale());
        });
        View::composer('layouts.store.navbar', function ($view) {
            $view->with('categories', $this->getProductsCategories());
        });
    }

    public function getProductsCategories()
    {
        return ProductsCategory::all();
    }
}
