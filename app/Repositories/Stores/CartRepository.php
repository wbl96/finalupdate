<?php

namespace App\Repositories\Stores;

use App\Interfaces\Stores\CartRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CartRepository implements CartRepositoryInterface
{
    public function index()
    {
        return Auth::user()->cart()
            ->with('items.detailKey')
            ->orderByDesc('id')->get();
    }

    public function addItem($cart, array $data)
    {
        return $cart->items()->create($data);
    }

    public function updateItem($item, array $data)
    {
        return $item->update($data);
    }

    public function updateCart($cart, array $data)
    {
        return $cart->update($data);
    }
}
