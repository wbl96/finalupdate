<?php

namespace App\Repositories\Suppliers;

use App\Interfaces\Suppliers\ProductCategoryRepositoryInterface;
use App\Models\ProductsCategory;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function index()
    {
        return ProductsCategory::with('subCategories')->get();
    }

    public function getById($id)
    {
        return ProductsCategory::find($id);
    }
}
