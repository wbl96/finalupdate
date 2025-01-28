<?php

namespace App\Repositories\Suppliers;

use App\Interfaces\Suppliers\ReportRepositoryInterface;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductSupplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportRepository implements ReportRepositoryInterface
{
    public function salesReports(string $period)
    {
        $supplierId = Auth::id();
        $orders = Order::whereHas('items', function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        })
            ->when($period, function ($query) use ($period) {
                return $this->applyPeriodFilter($query, $period);
            })
            ->orderByDesc('id')
            ->with(['store' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();
        // return orders
        return $orders;
    }

    public function paymentsReports(string $period)
    {
        $supplierId = Auth::id();
        $payments = Payment::forSupplier($supplierId)
            ->when($period, function ($query) use ($period) {
                return $this->applyPeriodFilter($query, $period);
            })
            ->orderByDesc('id')
            ->get();
        return $payments;
    }

    public function storesReports(string $period)
    {
        $supplierId = Auth::id();
        $products = ProductSupplier::where('supplier_id', $supplierId)
            ->when($period, function ($query) use ($period) {
                return $this->applyPeriodFilter($query, $period);
            })
            ->with('product')
            ->orderByDesc('id')
            ->get();
        return $products;
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
