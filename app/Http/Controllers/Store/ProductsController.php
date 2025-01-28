<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('store.product_modal', compact('product'));
    }
}
