<?php

namespace App\Repositories\Stores;

use App\Interfaces\Stores\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductRepository implements ProductRepositoryInterface
{
    public function index()
    {
        // get search value
        $search = request()->input('search');
        // get authenticated store
        $supplierIds  = Auth::user()->suppliers->pluck('id')->toArray();
        // get suppliers products
        return Product::when($search, function ($query, $search) {
            return $query->where('name_ar', 'like', "%{$search}%")
                ->orWhere('name_ar', 'like', "%{$search}%");
        })
            ->where('status', 'active')
            ->with('detail')
            ->orderByDesc('id')
            ->paginate(12);
    }

    public function getCategoriesProducts(string $category_id)
    {
        // get search value
        $search = request()->input('search');
        // get authenticated store
        $supplierIds  = Auth::user()->suppliers->pluck('id')->toArray();
        // get suppliers products
        return Product::when($search, function ($query, $search) {
            return $query->where('name_ar', 'like', "%{$search}%")
                ->orWhere('name_ar', 'like', "%{$search}%");
        })
            ->where('category_id', $category_id)
            ->where('status', 'active')
            ->with('detail')
            ->orderByDesc('id')
            ->paginate(12);
    }
}
