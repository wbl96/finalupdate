<?php

namespace App\Observers;

use App\Models\ProductsSubCategories;
use Illuminate\Support\Facades\Auth;

class ProductsSubCategoriesObserver
{
    /**
     * Handle the ProductsSubCategories "creating" event.
     */
    public function creating(ProductsSubCategories $productsSubCategory): void
    {
        $productsSubCategory->admin_id = Auth::id();
    }

    /**
     * Handle the ProductsSubCategories "updated" event.
     */
    public function updated(ProductsSubCategories $productsSubCategory): void
    {
        //
    }

    /**
     * Handle the ProductsSubCategories "deleted" event.
     */
    public function deleted(ProductsSubCategories $productsSubCategory): void
    {
        //
    }

    /**
     * Handle the ProductsSubCategories "restored" event.
     */
    public function restored(ProductsSubCategories $productsSubCategory): void
    {
        //
    }

    /**
     * Handle the ProductsSubCategories "force deleted" event.
     */
    public function forceDeleted(ProductsSubCategories $productsSubCategory): void
    {
        //
    }
}
