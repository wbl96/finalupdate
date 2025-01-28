<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\AddPaymentRequest;
use App\Http\Requests\Store\UpdateOrderStatusRequest;
use App\Http\Requests\Suppliers\Orders\RFQSubmitQuoteRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RFQSupplierResource;
use App\Interfaces\Suppliers\OrderRepositoryInterface;
use App\Models\Cart;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    private OrderRepositoryInterface $orderRepositoryInterface;

    public function __construct(OrderRepositoryInterface $orderRepositoryInterface)
    {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->orderRepositoryInterface->index();
        return ApiResponseClass::sendResponse(OrderResource::collection($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get order by id with details
        $order = $this->orderRepositoryInterface->getById($id);
        // dd($order);
        if ($order)
            return ApiResponseClass::sendResponse(new OrderResource($order));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderStatusRequest $request, string $id)
    {
        // prepare data to update it
        $data = [
            'status' => $request->status
        ];
        // start DB transaction
        DB::beginTransaction();
        try {
            // store order data
            $order = $this->orderRepositoryInterface->update($data, $id);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(trans('global.updated'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Add payment
     */
    public function addPayment(AddPaymentRequest $request, string $id)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // get order
            $order = $this->orderRepositoryInterface->getById($id);
            // get order invoice
            $invoice = $order->invoice;
            // get payment amount
            $paymentAmount = $request->payment_amount;
            // check payment amount
            if ($paymentAmount > $invoice->remaining_amount) {
                return ApiResponseClass::sendResponse(null, trans('orders.payment amount more than remaining'), false);
            }
            // update invoice
            $invoice->paid_amount += $paymentAmount;
            $invoice->remaining_amount = $invoice->total_amount - $invoice->paid_amount;
            $invoice->updateStatus();
            // prapare data to add it
            $data = [
                'invoice_id' => $invoice->id,
                'payment_amount' => $paymentAmount,
                'remaining_amount' => $invoice->remaining_amount,
                'payment_date' => now(),
            ];
            // create payment
            $payment = $this->orderRepositoryInterface->addPayment($data);
            // update order payment status
            if ($invoice->remaining_amount == 0) {
                $order->payment_status = 'paid';
                $order->saveQuietly();
            }
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(trans('orders.payment batch inserted'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    public function getRfqRequests()
    {
        $rfqRequests = $this->orderRepositoryInterface->getRfqRequests();
        return ApiResponseClass::sendResponse(RFQSupplierResource::collection($rfqRequests));
    }

    public function submitQuote(RFQSubmitQuoteRequest $request)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // validated data
            $validatedData = $request->validated();
            // data1
            $data1 = [
                'cart_item_id' => $validatedData['cart_item_id'],
                'detail_key_id' => $validatedData['detail_key_id'],
                'store_id' => $validatedData['store_id'],
                'status' => 'pending',
                'supplier_id' => Auth::id(),
            ];
            // data2
            $data2 = [
                'cart_item_id' => $validatedData['cart_item_id'],
                'detail_key_id' => $validatedData['detail_key_id'],
                'store_id' => $validatedData['store_id'],
                'qty' => $validatedData['quantity'],
                'proposed_price' => $validatedData['price'],
            ];
            // submit quote
            $this->orderRepositoryInterface->submitQuote($data1, $data2);

            $store = Store::query()->find($validatedData['store_id']);
            if ($store && $store->fcm_token) {
                $this->pushFCMNotifaication($store->fcm_token, 'You have new qoutation', '');
            }
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('orders.rfq submitted'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }
}
