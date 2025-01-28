<?php

namespace App\Repositories\Admins;

use App\Interfaces\Admins\FaqsRepositoryInterface;
use App\Models\Faq;

class FaqsRepository implements FaqsRepositoryInterface
{
    public function index()
    {
        return Faq::all();
    }

    public function store(array $data)
    {
        return Faq::create($data);
    }

    public function getById($id)
    {
        return Faq::find($id);
    }

    public function update(array $data, $id)
    {
        return Faq::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        Faq::where('id', $id)->delete();
    }
}
