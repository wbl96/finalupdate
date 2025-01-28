<?php

namespace App\Repositories\Stores;

use App\Interfaces\Stores\SupplierRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function index()
    {
        // get search value
        $search = request()->input('search');
        // return suppliers
        return Auth::user()->suppliers ? Auth::user()->suppliers()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            })
            ->withCount(['orders' => function ($query) {
                $query->whereNotIn('status', ['pending', 'refunded'])
                    ->whereNotIn('payment_status', ['pending', 'refunding']);
            }])
            ->orderByDesc('id')
            ->get() : [];
    }

    public function store(array $data)
    {
        return User::createOrFirst([
            'email' => $data['email'],
            'mobile' => $data['mobile'],
        ], $data);
    }
}
