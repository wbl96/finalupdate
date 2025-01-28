<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ServicesObserver
{
    /**
     * Handle the Service "creating" event.
     */
    public function creating(Service $service): void
    {
        $service->created_by = Auth::id();
        $service->updated_by = Auth::id();
    }
    
    /**
     * Handle the Service "updating" event.
     */
    public function updating(Service $service): void
    {
        $service->updated_by = Auth::id();
    }
}
