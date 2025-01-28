<?php

namespace App\Repositories\Suppliers;

use App\Interfaces\Suppliers\PolicyRepositoryInterface;
use App\Models\SuppliersPolicy;
use Illuminate\Support\Facades\Auth;

class PolicyRepository implements PolicyRepositoryInterface
{
    public function get()
    {
        return SuppliersPolicy::firstOrCreate(
            ['supplier_id' => Auth::id()],
            ['policy' => ''],
        );
    }

    public function update(array $data)
    {
        return SuppliersPolicy::updateOrCreate(
            ['supplier_id' => Auth::id()],
            $data,
        );
    }
}
