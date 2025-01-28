<?php

namespace App\Repositories\Admins;

use App\Interfaces\Admins\AdminRepositoryInterface;
use App\Models\Admin;

class AdminRepository implements AdminRepositoryInterface
{

    public function index()
    {
        // get search value
        $search = request()->input('search');
        return Admin::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', "%{$search}%");
        })->get() ?? [];
    }

    public function getById($id)
    {
        return Admin::find($id);
    }

    public function store(array $data)
    {
        return Admin::create($data);
    }

    public function update(array $data, $id)
    {
        return Admin::whereId($id)->update($data);
    }

    public function delete($id)
    {
        Admin::destroy($id);
    }
}
