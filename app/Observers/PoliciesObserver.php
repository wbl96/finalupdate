<?php

namespace App\Observers;

use App\Models\SuppliersPolicy;
use Illuminate\Support\Facades\Auth;

class PoliciesObserver
{
    /**
     * Handle the SuppliersPolicy "creating" event.
     */
    public function creating(SuppliersPolicy $policy): void
    {
        $policy->supplier_id = Auth::id();
    }
}
