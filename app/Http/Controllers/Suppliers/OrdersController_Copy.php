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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class OrdersController_Copy extends Controller
{
    public function index()
    {
        return view('supplier.orders.list');
    }

    public function getOrders()
    {
        $supplierOrders = Order::with(['items' => function ($query) {
            $query->where('supplier_id', Auth::id());
        }])->get() ?? [];

        // dd($ss);
        // // get orders
        // $supplierOrders = Auth::user()->orderItems()->orderByDesc('id')->get() ?? [];
        // Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['store', 'status', 'payment_status', 'created_at', 'controls'] : $columns;
        // return data into datatables
        return DataTables::of($supplierOrders)
            ->addIndexColumn()
            ->addColumn('store', function ($order) {
                return $order->store->name;
            })
            ->addColumn('total_price', function ($order) {
                return $order->total_price . ' ' . trans('global.SAR');
            })
            ->addColumn('status', function ($order) {
                return trans('orders.' . $order->status);
            })
            ->addColumn('payment_status', function ($order) {
                return trans('orders.' . $order->payment_status);
            })
            ->addColumn('controls', function ($order) {
                $btn = '<a href="' . route('supplier.orders.show', [$order]) . '" class="btn btn-import btn-sm"><i class="fa fa-eye"></i></a>';
                return $btn ?? '';
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    public function show(Order $order)
    {
        // dd($order);
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
        $cartItems = CartItem::query()
            ->select([
                'cart_items.id as id',
                'cart_id',
                'cart_items.detail_key_id',
                'products_detail_user.detail_key_id',
                'store_id',
                'rfq_requests_status',
                'min_order_qty',
                'products_detail_id',
                'name as varint_name',
                'key as varint_key',
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
            ->get();

        if (request()->ajax()) {
            // get rfq requests
            $carts = Cart::where('rfq_requests_status', 'open')->with('items.detail.product')->get();
            // Retrieve requested columns
            $columns = request()->columns ? array_column(request()->columns, 'data') : [];
            // html columns
            $htmlColumns = empty($columns) ?  ['store', 'total_items', 'rfq_requests', 'created_at', 'controls'] : $columns;

            return DataTables::of($carts)
                ->addIndexColumn()
                ->addColumn('store', function ($cart) {
                    return $cart->store->name;
                })
                ->addColumn('total_items', function ($cart) {
                    return $cart->items->count();
                })
                ->addColumn('cart_requests', function ($cart) {
                    return trans('orders.' . $cart->rfq_requests_status);
                })
                ->addColumn('controls', function ($cart) {
                    $btn = '<a href="' . route('supplier.orders.submit-quote', $cart) . '" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i>&nbsp;' . trans('orders.submit quote') . '</a>';
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

    public function submitQuote(Request $request, Cart $cart)
    {
        $request->validate([
            'cart_item_id' => 'array',
            'cart_item_qty' => 'array',
            'cart_item_proposed_price' => 'array',
        ]);

        $cartItemsIds = $request->cart_item_id;

        foreach ($cartItemsIds as $key => $id) {
            RfqRequest::create([
                'cart_item_id' => $id,
                'supplier_id' => Auth::id(),
                'proposed_price' => $request->cart_item_proposed_price[$key],
                'qty' => $request->cart_item_qty[$key],
            ]);
        }

        return back()->with('success', trans('orders.rfq sumitted'));
    }
}
