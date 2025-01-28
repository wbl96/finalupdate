<?php

namespace App\Repositories\Stores;

use App\Interfaces\Stores\ReportRepositoryInterface;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportRepository implements ReportRepositoryInterface
{
    public function purchasesReports(string $period)
    {
        $storeId = Auth::id();
        $orders = Order::forStore(Auth::id())
            ->when($period, function ($query) use ($period) {
                return $this->applyPeriodFilter($query, $period);
            })
            ->orderByDesc('id')
            ->get();
        // // appends report type
        // $orders->appends('report_type', $period);
        // return orders
        return $orders;
    }

    public function paymentsReports(string $period)
    {
        $payments = Order::forStore(Auth::id())
            ->when($period, function ($query) use ($period) {
                return $this->applyPeriodFilter($query, $period);
            })
            ->orderByDesc('id')
            ->get();
        return $payments;
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
