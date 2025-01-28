<?php

namespace App\Interfaces\Admins;

interface ProductCategoriesRepositoryInterface
{
    public function index();
    public function store(array $data);
    public function getById($id);
    public function update(array $data, $id);
    public function delete($id);
}
