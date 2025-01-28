<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ConfirmOrderRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductsDetailUser;
use App\Models\RfqRequest;
use App\Notifications\RFQNotification;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
{
    public function index()
    {
        return view('store.orders.list');
    }

    public function getOrders()
    {
        // get orders
        $orders = Auth::user()->orders()->orderByDesc('id')->get();
      // dd($orders);// Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['status', 'total_items', 'created_at', 'controls'] : $columns;
        // return data into datatables
        return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('total_price', function ($order) {
                return $order->total_price . ' ' . trans('global.SAR');
            })
            ->addColumn('total_items', function ($order) {
                return $order->items->count();
            })
            ->addColumn('status', function ($order) {
                return trans('orders.' . $order->status);
            })
            ->addColumn('controls', function ($order) {
                $btn = '<a href="' . route('store.orders.show', [$order]) . '" class="btn btn-import btn-sm"><i class="fa fa-eye"></i></a>';
                return $btn ?? '';
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    // public function confirm(ConfirmOrderRequest $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         // create a new order
    //         $order = Order::updateOrCreate([
    //             'cart_id' => Auth::user()->cart->id,
    //             'store_id' => Auth::id()
    //         ]);

    //         // update cart
    //         Auth::user()->cart->update([
    //             'rfq_requests_status' => 'close',
    //         ]);

    //         // get validated data
    //         $validated = $request->validated();
    //         // loop on order items to push it into order
    //         foreach ($validated['rfq_id'] as $index => $rfqId) {
    //             $rfq = RfqRequest::find($rfqId);
    //             $product_id = $rfq->cartItem->detailKey->detail->product_id;

    //             $order->items()->updateOrCreate([
    //                 'supplier_id' => $rfq->supplier_id,
    //                 'product_id' => $product_id,
    //                 'quantity' => $rfq->qty,
    //                 'price' => $rfq->proposed_price
    //             ]);

    //             $rfq->update([
    //                 'status' => 'approved'
    //             ]);
    //         }

    //         // update order total price
    //         $order->update([
    //             'total_price' => $order->items()->sum('price')
    //         ]);
    //         DB::commit();
    //         return back()->with('success', trans('orders.order confirmed'));
    //     } catch (\Exception $e) {
    //         Log::alert($e);
    //         DB::rollBack();
    //         return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
    //     }
    // }

    public function requestForQuotations(Request $request)
    {
        
        $validator = Validator::make($request->input(),[
            'qty' => 'required|array',
            'qty.*' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors(['error' => $validator->errors()])->withInput()->throwResponse();
        }
        
        DB::beginTransaction();
        try {
            $validate = $validator->validated();

            Cart::query()->where('store_id', auth()->id())->where('rfq_requests_status' , 'pending')
                ->update(['rfq_requests_status' => 'open']);

            // loop on keys id
            foreach ($validate['qty'] as $key => $value) {
                // get all suppliers with cart items
                $details = ProductsDetailUser::where('detail_key_id', $key)
                    // TODO :: this 2 conditions will active in feature
                    // ->where('min_order_qty', '<', $value)
                    // ->where('qty', '>', 0)
                    ->with('supplier')
                    ->get();

                foreach ($details as $key => $detail) {
                    $supplier = $detail->supplier;
                    $supplier->notify(new RFQNotification(Auth::user()->name));
                    $this->pushFCMNotifaication($supplier->fcm_token, trans('orders.rfq requests'), trans('orders.store want rfq request'));
                }
            }
            DB::commit();
            return back()->with('success', trans('orders.rfq requests open'));
        } catch (\Exception $e) {
            Log::alert($e);
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function show(Order $order)
    {
        // التحقق من سلة المشتريات والطلبات
        $cartItems = \DB::table('cart_items')
            ->where('store_id', auth()->id())
            ->get();

        $pendingRfqs = \DB::table('rfq_requests')
            ->whereIn('cart_item_id', $cartItems->pluck('id'))
            ->where('status', 'pending')
            ->get();

        dd([
            'order_details' => [
                'id' => $order->id,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'store_id' => $order->store_id
            ],
            'cart_items' => $cartItems->toArray(),
            'pending_rfqs' => $pendingRfqs->toArray(),
            'current_store' => auth()->id()
        ]);

        return view('store.orders.show', compact('order'));
    }

    public function uploadReceipt(Request $request, Order $order)
    {
        $request->validate([
            'bank_transfer_payment' => 'required'
        ]);

        // check product image
        if ($request->hasFile('bank_transfer_payment')) {
            if ($order->image && Storage::disk('public')->exists($order->image)) {
                Storage::disk('public')->delete($order->image);
            }
            // get file
            $file = $request->file('bank_transfer_payment');
            // save file into products folder
            $path = $file->store('paymentReceipt', 'public');
            // update image path in DB
            $order->update([
                'payment_receipt' => $path,
            ]);
        }

        return back()->with('success', trans('global.updated'));
    }
}