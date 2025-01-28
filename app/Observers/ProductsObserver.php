<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductsObserver
{
    /**
     * Handle the Product "creating" event.
     */
    public function creating(Product $product): void
    {
        if (get_class(Auth::user()) == "App\Models\User") {
            $product->supplier_id = Auth::id();
        }
    }
}
