<?php

namespace App\Interfaces\Stores;

interface ProductRepositoryInterface
{
    public function index();
    public function getCategoriesProducts(string $category_id);
}
