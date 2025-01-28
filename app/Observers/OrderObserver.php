<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     */
    public function creating(Order $order): void
    {
        $order->store_id = Auth::id();
    }

    /**
     * Handle the Order "saving" event.
     */
    public function saving(Order $order): void
    {
        // // check order status
        // if (in_array($order->status, ['pending', 'refunded'])) {
        //     $order->payment_status = $order->status;
        // } else {
        //     $order->payment_status = 'deserved';
        // }
    }
}
