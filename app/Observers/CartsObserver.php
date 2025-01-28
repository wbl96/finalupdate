<?php

namespace App\Observers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartsObserver
{
    /**
     * Handle the Cart "creating" event.
     */
    public function creating(Cart $cart): void
    {
        $cart->store_id = Auth::id();
    }
}
