<?php

namespace App\Observers;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ClientsObserver
{
    /**
     * Handle the Client "creating" event.
     */
    public function creating(Client $client): void
    {
        $client->supplier_id = Auth::id();
    }
}
