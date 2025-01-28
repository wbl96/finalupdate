<?php

namespace App\Interfaces\Stores;

interface ProductCategoryRepositoryInterface
{
    public function index();
    public function getById($id);
}
