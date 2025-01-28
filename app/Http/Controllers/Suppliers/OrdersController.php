<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\AddPaymentRequest;
use App\Http\Requests\Store\UpdateOrderStatusRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\RfqRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
{
    public function index()
    {
        return view('supplier.orders.list');
    }

    public function getOrders()
    {

        $orderItem = OrderItem::query()
            ->select([
                'order_items.id as id',
                'order_items.created_at',
                'order_id',
                'order_items.detail_key_id',
                'order_items.quantity',
                'orders.status',
                'products_detail_id',
                'name as variant_name',
                'key as variant_key',
                'product_id',
                'name_' . config('app.locale') . ' as name',
                'sku',
                'supplier_id',
            ])
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products_details_keys', 'order_items.detail_key_id', '=', 'products_details_keys.id')
            ->join('products_details', 'products_details_keys.products_detail_id', '=', 'products_details.id')
            ->join('products', 'products_details.product_id', '=', 'products.id')
            ->where([
                ['supplier_id', auth()->id()],
                ['orders.status', '!=', 'pendeng'],
            ])
            ->get();
        // Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['order_num', 'product', 'product_detail', 'price', 'quantity', 'total_amount', 'status', 'created_at'] : $columns;

        // return data into datatables
        return DataTables::of($orderItem)
            ->addIndexColumn()
            ->addColumn('order_num', function ($order) {
                return $order->id + 10000;
            })
            ->addColumn('status', function ($order) {
                $status = trans('orders.new');
                $status = match ($order->status) {
                    'pending' => trans('orders.' . $order->status),
                    'new' => trans('orders.' . $order->status),
                    'dispatched' => trans('orders.' . $order->status),
                    'delivered' => trans('orders.' . $order->status),
                    'reject' => trans('orders.' . $order->status),
                    'canceled' => trans('orders.' . $order->status),
                    'refunded' => trans('orders.' . $order->status),
                };
                return $status;
            })
            ->addColumn('price', function ($order) {
                return $order->price . ' ' . trans('global.SAR');
            })
            ->addColumn('product', function ($order) {
                return $order->name;
            })
            ->addColumn('product_detail', function ($order) {
                return $order->variant_name . ' : ' . $order->variant_key;
            })
            ->addColumn('quantity', function ($order) {
                return $order->quantity;
            })
            ->addColumn('total_amount', function ($order) {
                return ($order->quantity * $order->price) . ' ' . trans('global.SAR');
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    public function show(Order $order)
    {
        // dd($order->items);
        return view('supplier.orders.show', compact('order'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // update order status
            $order->update($request->validated());
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('orders.order status updated', ['status' => trans('orders.' . $order->status)]));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function addPayment(AddPaymentRequest $request, Order $order)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // get order invoice
            $invoice = $order->invoice;
            // get payment amount
            $paymentAmount = $request->validated('payment_amount');
            // check if payment amount > remaining amount
            if ($paymentAmount > $invoice->remaining_amount) {
                DB::rollBack();
                return back()->withErrors(['error' => trans('orders.payment amount more than remaining')]);
            }

            // update invoice
            $invoice->paid_amount += $paymentAmount;
            $invoice->remaining_amount = $invoice->total_amount - $invoice->paid_amount;
            $invoice->updateStatus();
            // create a payment
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'payment_amount' => $paymentAmount,
                'remaining_amount' => $invoice->remaining_amount,
                'payment_date' => now(),
            ]);

            // update order payment status
            if ($invoice->remaining_amount == 0) {
                $order->payment_status = 'paid';
                $order->saveQuietly();
            }
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('orders.payment batch inserted'));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function getRfqRequests()
    {
        if (request()->ajax()) {
            // CartItem::query()->whereDoesntHave('rfqRequests')->get()
            $cartItems = CartItem::query()
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
            // Retrieve requested columns
            $columns = request()->columns ? array_column(request()->columns, 'data') : [];
            // html columns
            $htmlColumns = empty($columns) ?  ['image', 'product', 'variant', 'quantity', 'created_at', 'controls'] : $columns;

            return DataTables::of($cartItems)
                ->addIndexColumn()
                ->addColumn('image', function ($cart) {
                    return '<img src="' . url('storage/' . $cart->image) . '" height="40">';
                })
                ->addColumn('product', function ($cart) {
                    $name = config('app.locale') == 'ar' ? 'name_ar' : 'name_en';
                    return $cart->$name;
                })
                ->addColumn('variant', function ($cart) {
                    return $cart->variant_name . ' : ' . $cart->variant_key;
                })
                ->addColumn('quantity', function ($cart) {
                    return $cart->qty;
                })
                ->addColumn('controls', function ($cart) {
                    $btn = '<button class="btn btn-sm btn-success btn-open-rfq" onclick="btnRFQ_click(this)"><i class="bi bi-check-lg"></i>&nbsp;' . trans('orders.submit quote') . '</button>';
                    return $btn;
                })
                ->rawColumns($htmlColumns)
                ->make(true);
        }

        return view('supplier.orders.rfq');
    }

    public function submitQuoteView(Cart $cart)
    {
        return view('supplier.orders.submit', get_defined_vars());
    }

    public function submitQuote(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'cart_item_id' => 'required|exists:cart_items,id',
            'detail_key_id' => 'required|exists:products_details_keys,id',
            'store_id' => 'required|exists:stores,id',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()]);
        }

        $data = $validator->validated();

        RfqRequest::query()->updateOrCreate([
            'cart_item_id' => $data['cart_item_id'],
            'detail_key_id' => $data['detail_key_id'],
            'store_id' => $data['store_id'],
            'status' => 'pending',
            'supplier_id' => Auth::id(),
        ], [
            'cart_item_id' => $data['cart_item_id'],
            'detail_key_id' => $data['detail_key_id'],
            'store_id' => $data['store_id'],
            'qty' => $data['quantity'],
            'proposed_price' => $data['price'],
        ]);

        $store = Store::query()->find($data['store_id']);
        if ($store) {
            $this->pushFCMNotifaication($store->fcm_token, 'You have new qoutation', '');
        }

        return response(['success' => trans('orders.rfq submitted')]);
    }
}
