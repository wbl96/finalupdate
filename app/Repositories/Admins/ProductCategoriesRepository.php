<?php

namespace App\Repositories\Admins;

use App\Interfaces\Admins\ProductCategoriesRepositoryInterface;
use App\Models\ProductsCategory;

class ProductCategoriesRepository implements ProductCategoriesRepositoryInterface
{
    public function index()
    {
        return ProductsCategory::all();
    }

    public function store(array $data)
    {
        return ProductsCategory::create($data);
    }

    public function getById($id)
    {
        return ProductsCategory::find($id);
    }

    public function update(array $data, $id)
    {
        return ProductsCategory::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        ProductsCategory::where('id', $id)->delete();
    }
}
