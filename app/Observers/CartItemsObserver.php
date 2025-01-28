<?php

namespace App\Observers;

use App\Models\CartItem;

class CartItemsObserver
{
    /**
     * Handle the CartItem "created" event.
     */
    public function created(CartItem $cartItem): void
    {
        //
    }

    /**
     * Handle the CartItem "updated" event.
     */
    public function updated(CartItem $cartItem): void
    {
        //
    }

    /**
     * Handle the CartItem "deleted" event.
     */
    public function deleted(CartItem $cartItem): void
    {
        // get cart
        $cart = $cartItem->cart;
        // update cart
        $cart->update([
            'total_items' => $cart->items()->count()
        ]);
    }

    /**
     * Handle the CartItem "restored" event.
     */
    public function restored(CartItem $cartItem): void
    {
        //
    }

    /**
     * Handle the CartItem "force deleted" event.
     */
    public function forceDeleted(CartItem $cartItem): void
    {
        //
    }
}
