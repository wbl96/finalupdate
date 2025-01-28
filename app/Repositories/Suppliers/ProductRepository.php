<?php

namespace App\Repositories\Suppliers;

use App\Interfaces\Suppliers\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\ProductsDetailUser;
use App\Models\ProductSupplier;
use Illuminate\Support\Facades\Auth;

class ProductRepository implements ProductRepositoryInterface
{
    public function index()
    {
        $type = request()->input('get') ?? null;

        return $type ?
            Product::with('subcategory', 'detail.keys')->get() :
            ProductSupplier::query()
            ->where('supplier_id', Auth::id())
            ->with('supplier', 'product.detail.keys')
            ->orderByDesc('id')->get();
    }

    public function getById($id)
    {
        return ProductSupplier::query()
            ->where('supplier_id', Auth::id())
            ->where('product_id', $id)
            ->with('supplier', 'product.detail.keys')
            ->first();
    }

    public function store(array $data)
    {
        // create product with supplier
        ProductSupplier::create([
            'supplier_id' => Auth::id(),
            'product_id' => $data['product_id'],
            'status' => 'new'
        ]);

        // loop on details keys id
        foreach ($data['details_key_id'] as $key => $keyId) {
            ProductsDetailUser::updateOrCreate([
                'supplier_id' => Auth::id(),
                'detail_key_id' => $keyId,
            ], [
                'min_order_qty' => $data['min_order_qty'][$key],
                'qty' => $data['qty'][$key],
            ]);
        }
        return true;
    }

    public function update(array $data, $id)
    {
        // loop on details keys id
        foreach ($data['details_key_id'] as $key => $keyId) {
            // dd($keyId);
            ProductsDetailUser::updateOrCreate([
                'supplier_id' => Auth::id(),
                'detail_key_id' => $keyId,
            ], [
                'min_order_qty' => $data['min_order_qty'][$key],
                'qty' => $data['qty'][$key],
            ]);
        }
        return true;
    }

    public function delete($id)
    {
        $product = ProductSupplier::find($id);
        // get supplier id
        $supplierId = $product->supplier_id;
        // get product details keys
        $productDetailsKeysIds = $product->product->detail->keys->pluck('id')->toArray();
        // delete product detail user
        ProductsDetailUser::where('supplier_id', $supplierId)
            ->whereIn('detail_key_id', $productDetailsKeysIds)
            ->delete();
        // delete product
        $product->delete();
    }
}
