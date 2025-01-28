<?php

namespace App\Http\Controllers\Suppliers;

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
    public function getSubcategories(ProductsCategory $category)
    {
        return $category->subCategories()->select('id', 'name_ar', 'name_en')->get();
    }
}
