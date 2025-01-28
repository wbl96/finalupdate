<?php

namespace App\Interfaces\Stores;

interface OrderRepositoryInterface
{
    public function index();
    public function getById($id);
}
