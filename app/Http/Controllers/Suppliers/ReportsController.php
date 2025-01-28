<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function index()
    {
        return view('supplier.reports.index');
    }

    public function salesReports(Request $request)
    {
        // get report period
        $period = $request->input('period', 'all');
        $supplierId = Auth::id();
        // get orders
        $orders = Order::whereHas('items', function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        })->when($period, function ($query) use ($period) {
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

        return view('supplier.reports.sales', compact('orders', 'message'));
    }

    public function paymentsReports(Request $request)
    {
        // get report period
        $period = $request->input('period', 'all');
        // get payment of supplier
        $payments = Payment::forSupplier(Auth::id())
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

        return view('supplier.reports.payments', compact('payments', 'period', 'message'));
    }

    public function productsReports(Request $request)
    {
        // get report period
        $period = $request->input('period', 'all');
        // query for product
        $products = Product::where('supplier_id', Auth::id())->when($period, function ($query) use ($period) {
            return $this->applyPeriodFilter($query, $period);
        })
            ->orderByDesc('id')
            ->paginate();

        if ($products->isEmpty()) {
            $message = "لا توجد نتائج في الفترة المحددة.";
        } else {
            $products->appends('period', $period);
            $message = null;
        }

        return view('supplier.reports.products', compact('products', 'period', 'message'));
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
