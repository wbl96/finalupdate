<?php

namespace App\Observers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoicesObserver
{
    /**
     * Handle the Invoice "creating" event.
     */
    public function creating(Invoice $invoice): void
    {
        $invoice->store_id = Auth::id();
        if (is_null($invoice->remaining_amount)) {
            $invoice->remaining_amount = $invoice->total_amount;
        }
    }
}
