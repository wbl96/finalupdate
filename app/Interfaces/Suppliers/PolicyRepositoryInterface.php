<?php

namespace App\Interfaces\Suppliers;

interface PolicyRepositoryInterface
{
    public function get();
    public function update(array $data);
}
