<?php

namespace App\Observers;

use App\Models\ProductsCategory;
use Illuminate\Support\Facades\Auth;

class ProductsCategoriesObserver
{
    /**
     * Handle the ProductsCategory "creating" event.
     */
    public function creating(ProductsCategory $productsCategory): void
    {
        $productsCategory->admin_id = Auth::id();
    }

    /**
     * Handle the ProductsCategory "updated" event.
     */
    public function updated(ProductsCategory $productsCategory): void
    {
        //
    }

    /**
     * Handle the ProductsCategory "deleted" event.
     */
    public function deleted(ProductsCategory $productsCategory): void
    {
        //
    }

    /**
     * Handle the ProductsCategory "restored" event.
     */
    public function restored(ProductsCategory $productsCategory): void
    {
        //
    }

    /**
     * Handle the ProductsCategory "force deleted" event.
     */
    public function forceDeleted(ProductsCategory $productsCategory): void
    {
        //
    }
}
