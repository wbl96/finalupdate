<?php

namespace App\Repositories\Suppliers;

use App\Interfaces\Suppliers\ClientRepositoryInterface;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientRepository implements ClientRepositoryInterface
{
    public function index()
    {
        return Auth::user()->stores ? Auth::user()->stores()
            ->withCount(['orders' => function ($query) {
                $query->whereNotIn('status', ['pending', 'refunded'])
                    ->whereNotIn('payment_status', ['pending', 'refunding']);
            }])
            ->orderByDesc('id')->get() : [];
    }

    public function getById($id)
    {
        return Auth::user()->stores()->where('stores.id', $id)->first();
    }

    public function store(array $data)
    {
        // get image
        $image = $data['image'];
        // check if data has an image
        if ($image) {
            // store image
            $path = $image->store('stores', 'public');
            // change image in data
            $data['image'] = $path;
        }
        // create a new store
        return Store::create($data);
    }

    public function update(array $data, $id)
    {
        // get store
        $store = Store::find($id);
        // get image
        $image = $data['image'];
        // check if data has an image
        if ($image && $image->isValid()) {
            // check if store has already
            if ($store->image && Storage::disk('public')->exists($store->image)) {
                Store::disk('public')->delete($store->image);
            }
            // store image
            $path = $image->store('stores', 'public');
            // change image in data
            $data['image'] = $path;
        }
        return $store->update($data);
    }

    public function delete($id)
    {
        Store::destroy($id);
    }
}
