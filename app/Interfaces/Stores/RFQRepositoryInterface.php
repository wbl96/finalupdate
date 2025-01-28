<?php

namespace App\Interfaces\Stores;

interface RFQRepositoryInterface
{
    public function index();
    public function store(array $data);
}
