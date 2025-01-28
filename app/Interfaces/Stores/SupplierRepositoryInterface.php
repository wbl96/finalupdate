<?php

namespace App\Interfaces\Stores;

interface SupplierRepositoryInterface
{
    public function index();
    public function store(array $data);
}
