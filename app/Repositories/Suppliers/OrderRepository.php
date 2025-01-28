<?php

namespace App\Repositories\Suppliers;

use App\Interfaces\Suppliers\OrderRepositoryInterface;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\RfqRequest;
use Illuminate\Support\Facades\Auth;

class OrderRepository implements OrderRepositoryInterface
{
    public function index()
    {
        return Order::with(['items' => function ($query) {
            $query->where('supplier_id', Auth::id());
        }])->orderByDesc('id')->get();
    }

    public function getById($id)
    {
        return Order::with(['items' => function ($query) {
            $query->where('supplier_id', Auth::id());
        }])->find($id);
    }

    public function store(array $data)
    {
        return Order::create($data);
    }

    public function update(array $data, $id)
    {
        return Order::whereId($id)->update($data);
    }

    public function getRfqRequests()
    {
        return  CartItem::query()
            ->select([
                'cart_items.id as id',
                'cart_items.created_at',
                'cart_id',
                'cart_items.detail_key_id',
                'products_detail_user.detail_key_id',
                'store_id',
                'rfq_requests_status',
                'cart_items.qty',
                'min_order_qty',
                'products_detail_id',
                'name as variant_name',
                'key as variant_key',
                'product_id',
                'image',
                'name_ar',
                'name_en',
                'sku',
                'supplier_id',
                'products_detail_user.qty as supplier_product_qty',
            ])
            ->join('carts', 'cart_items.cart_id', '=', 'carts.id')
            ->join('products_detail_user', 'cart_items.detail_key_id', '=', 'products_detail_user.detail_key_id')
            ->join('products_details_keys', 'cart_items.detail_key_id', '=', 'products_details_keys.id')
            ->join('products_details', 'products_details_keys.products_detail_id', '=', 'products_details.id')
            ->join('products', 'products_details.product_id', '=', 'products.id')
            ->where([
                ['carts.rfq_requests_status', 'open'],
                ['products_detail_user.supplier_id', Auth::id()],
                ['min_order_qty', '>=', 'qty'],
                ['products_detail_user.qty', '>=', 1],
            ])
            ->whereDoesntHave('rfqRequests')
            ->get();
    }

    public function submitQuote(array $data1, array $data2)
    {
        return RfqRequest::query()->updateOrCreate($data1, $data2);;
    }


    public function addPayment(array $data)
    {
        return Payment::create($data);
    }

    public function delete($id)
    {
        Order::destroy($id);
    }
}
