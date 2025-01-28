<?php

namespace App\Interfaces\Suppliers;

interface OrderRepositoryInterface
{
    public function index();
    public function getById($id);
    public function store(array $data);
    public function update(array $data, $id);
    public function getRfqRequests();
    public function submitQuote(array $data1, array $data2);
    public function addPayment(array $data);
    public function delete($id);
}
