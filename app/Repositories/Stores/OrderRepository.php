<?php

namespace App\Repositories\Stores;

use App\Interfaces\Stores\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderRepository implements OrderRepositoryInterface
{
    public function index()
    {
        return Order::where('store_id', Auth::id())->orderByDesc('id')->paginate(10);
    }

    public function getById($id)
    {
        return Order::find($id);
    }
}
