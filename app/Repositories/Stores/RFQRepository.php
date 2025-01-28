<?php

namespace App\Repositories\Stores;

use App\Interfaces\Stores\RFQRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RfqRequest;
use Illuminate\Support\Facades\Auth;

class RFQRepository implements RFQRepositoryInterface
{
    public function index()
    {
        return RfqRequest::query()->where([
            'store_id' => Auth::id(),
            'status' => 'pending'
        ])->get();
    }

    public function store(array $data)
    {
        // close rfq requests
        RfqRequest::query()->where([
            'store_id' => Auth::id(),
            ['status', '!=', 'approved']
        ])->update([
            'status' => 'closed'
        ]);

        // create a new order
        $order = Order::query()->create([
            'store_id' => Auth::id(),
            'total_price' => $data['total_price'],
            // 'payment_receipt' => $data['path'],
        ]);
        // initial total price
        $total_price = 0;
        // loop on ids
        foreach ($data['data'] as $key => $d) {
            // get rfq request
            $rfq = RfqRequest::query()->where('id', $d['id'])->firstOrFail();
            // check data
            if ($rfq->id != $d['id'] || $rfq->supplier_id != $d['supplier_id'] || $rfq->detail_key_id != $d['detail_key_id']) {
                return false;
            }
            // update total price
            $total_price += $rfq->proposed_price * $rfq->qty;
            // create an order item
            OrderItem::query()->create([
                'rfq_request_id' => $rfq->id,
                'detail_key_id' => $rfq->detail_key_id,
                'supplier_id' => $rfq->supplier_id,
                'order_id' => $order->id,
                'quantity' => $rfq->qty,
                'price' => $rfq->proposed_price
            ]);
            // update rfq request
            $rfq->update([
                'status' => 'approved'
            ]);
            // update cart
            $rfq->cartItem->cart()->update([
                'rfq_requests_status' => 'close'
            ]);
        }
        // check total price
        if ($total_price != $data['total_price']) {
            return false;
        }
        // return order
        return $order;
    }
}
