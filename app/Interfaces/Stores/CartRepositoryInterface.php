<?php

namespace App\Interfaces\Stores;

use App\Models\Cart;
use App\Models\CartItem;

interface CartRepositoryInterface
{
    public function index();
    public function addItem(Cart $cart, array $data);
    public function updateItem(CartItem $item, array $data);
    public function updateCart(Cart $cart, array $data);
}
