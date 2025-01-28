<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function index()
    {
        return view('store.reports.index');
    }

    public function purchasesReports(Request $request)
    {
        // get report period
        $period = $request->input('period', 'all');
        // get orders
        $orders = Order::forStore(Auth::id())
            ->when($period, function ($query) use ($period) {
                return $this->applyPeriodFilter($query, $period);
            })
            ->orderByDesc('id')
            ->with('items')
            ->paginate();

        if ($orders->isEmpty()) {
            $message = "لا توجد نتائج في الفترة المحددة.";
        } else {
            $message = null;
            $orders->appends('period', $period);
        }

        return view('store.reports.purchases', compact('orders', 'message'));
    }

    public function paymentsReports(Request $request)
    {
        // get report period
        $period = $request->input('period', 'all');
        // get payment of store
        $payments = Order::forStore(Auth::id())
            ->when($period, function ($query) use ($period) {
                return $this->applyPeriodFilter($query, $period);
            })
            ->orderByDesc('id')
            ->paginate();

        if ($payments->isEmpty()) {
            $message = "لا توجد نتائج في الفترة المحددة.";
        } else {
            $payments->appends('period', $period);
            $message = null;
        }

        return view('store.reports.payments', compact('payments', 'period', 'message'));
    }


    private function applyPeriodFilter($query, string $period)
    {
        switch ($period) {
            case 'daily':
                $query->whereDate('created_at', Carbon::today());
                break;

            case 'weekly':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;

            case 'monthly':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;

            case 'all':
            default:
                break;
        }

        return $query;
    }
}
