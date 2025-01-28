<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
{
    public function index()
    {
        return view('admin.orders.list');
    }

    public function getOrders()
    {
        $orders = Order::with(['items.rfqRequst', 'store.wallet'])
            ->orderByDesc('id')
            ->get();

        $htmlColumns = ['order_num', 'store', 'total_price', 'wallet_used', 'bank_amount', 'payment_receipt', 'status', 'created_at', 'controls'];
        
        return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('order_num', function ($order) {
                return '#' . ($order->id + 10000);
            })
            ->addColumn('store', function ($order) {
                return $order->store->name ?? '';
            })
            ->addColumn('total_price', function ($order) {
                $total = $order->items->sum(function($item) {
                    return $item->price * $item->quantity;
                });
                return '<span class="text-primary">' . number_format($total, 2) . ' ' . trans('global.SAR') . '</span>';
            })
            ->addColumn('wallet_used', function ($order) {
                $walletAmount = WalletTransaction::where('order_id', $order->id)
                    ->where('type', 'credit_used')
                    ->sum('amount');
                
                if ($walletAmount > 0) {
                    return '<span class="text-success">' . number_format($walletAmount, 2) . ' ' . trans('global.SAR') . '</span>';
                }
                return '<span class="text-muted">0.00 ' . trans('global.SAR') . '</span>';
            })
            ->addColumn('bank_amount', function ($order) {
                $total = $order->items->sum(function($item) {
                    return $item->price * $item->quantity;
                });
                
                $walletAmount = WalletTransaction::where('order_id', $order->id)
                    ->where('type', 'credit_used')
                    ->sum('amount');
                
                $bankAmount = $total - $walletAmount;
                
                return '<span class="text-warning">' . number_format($bankAmount, 2) . ' ' . trans('global.SAR') . '</span>';
            })
            ->addColumn('payment_receipt', function ($order) {
                if ($order->payment_receipt) {
                    return '<a href="' . asset('storage/' . $order->payment_receipt) . '" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-receipt"></i> ' . trans('orders.show_receipt') . '</a>';
                }
                return '<span class="badge badge-secondary">' . trans('orders.no_receipt') . '</span>';
            })
            ->addColumn('status', function ($order) {
                $statusClasses = [
                    'pending' => 'warning',
                    'processing' => 'info',
                    'completed' => 'success',
                    'cancelled' => 'danger'
                ];
                $class = $statusClasses[$order->status] ?? 'secondary';
                return '<span class="badge badge-' . $class . '">' . trans('orders.' . $order->status) . '</span>';
            })
            ->addColumn('created_at', function ($order) {
                if ($order->created_at instanceof \Carbon\Carbon) {
                    return $order->created_at->format('Y-m-d H:i');
                }
                return $order->created_at;
            })
            ->addColumn('controls', function ($order) {
                return '<button class="btn btn-primary btn-sm" onclick="show_click(this, ' . $order->id . ')"><i class="fa fa-eye"></i></button>';
            })
            ->rawColumns(['total_price', 'wallet_used', 'bank_amount', 'payment_receipt', 'status', 'controls'])
            ->make(true);
    }

    public function show(Order $order)
    {
        $order->load(['items.rfqRequst', 'store.wallet']);
        
        // حساب المبالغ
        $total = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $walletAmount = WalletTransaction::where('order_id', $order->id)
            ->where('type', 'credit_used')
            ->sum('amount');
        
        $bankAmount = $total - $walletAmount;
        
        $orderDetails = [
            'total_price' => $total,
            'wallet_amount' => $walletAmount,
            'bank_amount' => $bankAmount,
            'store_wallet_balance' => $order->store->wallet ? $order->store->wallet->balance : 0
        ];
        
        return view('admin.orders.show', compact('order', 'orderDetails'));
    }

    public function approve(Request $request)
    {
        try {
            Order::query()->where('id', $request->order_id)->update([
                'status' => $request->orderStatus
            ]);
            return response(['success' => trans('global.updated')]);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()]);
        }
    }

    public function getOrderItems($id)
    {
        $items = OrderItem::query()->where('order_id', $id)->get();
        return view('admin.orders.items_list', compact('items', 'id'));
    }
}