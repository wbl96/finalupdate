<?php

namespace App\Observers;

use App\Models\Faq;
use Illuminate\Support\Facades\Auth;

class FaqsObserver
{
    /**
     * Handle the Faq "creating" event.
     */
    public function creating(Faq $faq): void
    {
        $faq->created_by = Auth::id();
    }
}
