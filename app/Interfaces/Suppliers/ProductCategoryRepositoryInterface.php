<?php

namespace App\Interfaces\Suppliers;

interface ProductCategoryRepositoryInterface
{
    public function index();
    public function getById($id);
}
