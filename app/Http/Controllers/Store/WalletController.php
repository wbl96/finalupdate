<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:store');
    }

    public function index()
    {
        $store = Auth::user();
        $wallet = $store->wallet;
        
        // المعاملات مرتبة حسب الأحدث
        $transactions = $wallet ? $wallet->transactions()
            ->latest()
            ->paginate(10) : collect();

        // المعاملات غير المسددة
        $unpaidTransactions = $wallet ? $wallet->transactions()
            ->where('is_paid', false)
            ->where('type', 'credit_used')
            ->whereNotNull('due_date')
            ->get() : collect();

        // إجمالي المبلغ غير المسدد
        $totalUnpaid = $unpaidTransactions->sum('amount');

        // إجمالي المستلم (credit)
        $totalCredit = $wallet ? $wallet->transactions()
            ->where('type', 'credit')
            ->sum('amount') : 0;

        // إجمالي المصروف (credit_used)
        $totalDebit = $wallet ? $wallet->transactions()
            ->where('type', 'credit_used')
            ->sum('amount') : 0;

        // عدد العمليات
        $transactionsCount = $wallet ? $wallet->transactions()->count() : 0;

        // تواريخ الاستحقاق للدفعات غير المسددة
        $dueDates = $unpaidTransactions->map(function($transaction) {
            return [
                'amount' => $transaction->amount,
                'due_date' => $transaction->due_date,
                'days_remaining' => now()->diffInDays($transaction->due_date, false),
                'is_overdue' => now()->gt($transaction->due_date)
            ];
        });

        \Log::info('Unpaid Transactions:', [
            'count' => $unpaidTransactions->count(),
            'total' => $totalUnpaid,
            'transactions' => $unpaidTransactions->toArray(),
            'dueDates' => $dueDates->toArray()
        ]);

        return view('store.wallet.index', compact(
            'wallet',
            'transactions',
            'totalCredit',
            'totalDebit',
            'transactionsCount',
            'totalUnpaid',
            'dueDates'
        ));
    }
} 