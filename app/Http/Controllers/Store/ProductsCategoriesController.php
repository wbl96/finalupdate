<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Ramsey\Uuid\v1;

class ProductsCategoriesController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(request $request, ProductsCategory $category)
    {
        // get search value
        $search = $request->input('search');
        // get suppliers products
        $products = Product::when($search, function ($query, $search) {
            return $query->where('name_ar', 'like', "%{$search}%")
                ->orWhere('name_ar', 'like', "%{$search}%");
        })
            ->where('category_id', $category->id)
            ->orderByDesc('id')
            ->paginate(12);

        // $products = $category->products()->paginate(12);

        if (Auth::check())
            return view('store.index', get_defined_vars());
        return view('welcome', get_defined_vars());
    }
}
