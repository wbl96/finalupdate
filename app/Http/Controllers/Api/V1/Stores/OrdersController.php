<?php

namespace App\Http\Controllers\Api\V1\Stores;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Interfaces\Stores\OrderRepositoryInterface;
use App\Models\Cart;
use App\Models\ProductsDetailUser;
use App\Notifications\RFQNotification;
use Illuminate\Http\Request;
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
        $orders = $this->orderRepositoryInterface->index();
        return ApiResponseClass::sendResponse(OrderResource::collection($orders));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get order by id
        $order = $this->orderRepositoryInterface->getById($id);
        if ($order)
            return ApiResponseClass::sendResponse(new OrderResource($order));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }

    public function requestForQuotations(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'data' => 'required|array',
                'qty.*.detail_key_id' => 'nullable|numeric',
                'qty.*.quantity' => 'nullable|numeric',
            ]);

            Cart::query()->where('store_id', Auth::id())->where('rfq_requests_status', 'pending')
                ->update(['rfq_requests_status' => 'open']);

            // loop on keys id
            foreach ($validate['data'] as $item) {
                // get all suppliers with cart items
                $details = ProductsDetailUser::where('detail_key_id', $item['detail_key_id'])
                    // TODO :: this 2 conditions will active in feature
                    // ->where('min_order_qty', '<', $item['quantity'])
                    // ->where('qty', '>', 0)
                    ->with('supplier')
                    ->get();

                foreach ($details as $key => $detail) {
                    $supplier = $detail->supplier;
                    $supplier->notify(new RFQNotification(Auth::user()->name));
                    if ($supplier->fcm_token) {
                        $this->pushFCMNotifaication($supplier->fcm_token, trans('orders.rfq requests'), trans('orders.store want rfq request'));
                    }
                }
            }
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('orders.rfq requests open'));
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return ApiResponseClass::rollback($e);
        }
    }
}
