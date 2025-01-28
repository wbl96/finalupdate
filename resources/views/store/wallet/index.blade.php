@extends('layouts.store.app')

@section('title', 'المحفظة')

@push('css')
<style>
    .wallet-section {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 30px 0;
    }

    .wallet-header {
        background: linear-gradient(135deg, #6c927f 0%, #2c5282 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 20px rgba(108, 146, 127, 0.2);
    }

    .wallet-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='rgba(255,255,255,0.05)' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .balance-title {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 10px;
    }

    .balance-amount {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .stats-income { background: #e8f5e9; color: #2e7d32; }
    .stats-expense { background: #ffebee; color: #c62828; }
    .stats-total { background: #e3f2fd; color: #1565c0; }

    .transactions-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .transaction-item {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .transaction-item:hover {
        background: #f8f9fa;
    }

    .transaction-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
    }

    .transaction-details {
        flex-grow: 1;
    }

    .transaction-amount {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .credit-amount { color: #2e7d32; }
    .debit-amount { color: #c62828; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 20px;
    }

    .pagination {
        margin: 20px 0;
        justify-content: center;
    }

    .unpaid-section {
        margin: 20px 0;
    }

    .unpaid-card {
        background: #fff3cd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .unpaid-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        color: #856404;
    }

    .unpaid-header i {
        font-size: 1.5rem;
        margin-left: 10px;
    }

    .unpaid-total {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 15px;
        color: #856404;
    }

    .unpaid-item {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .unpaid-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .unpaid-item.overdue {
        border-right: 4px solid #dc3545;
    }

    .due-date-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.9em;
        background: white;
        color: #856404;
    }

    .due-date-badge.overdue {
        background: #dc3545;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="wallet-section">
    <div class="container">
        <!-- Wallet Header -->
        <div class="wallet-header">
            <div class="balance-title">الرصيد الحالي</div>
            <div class="balance-amount">
                {{ number_format($wallet->balance ?? 0, 2) }} {{ trans('global.SAR') }}
            </div>
            <div class="text-white-50">
                آخر تحديث: {{ optional($wallet)->updated_at ? $wallet->updated_at->format('Y-m-d H:i') : 'لا يوجد' }}
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stats-card">
                <div class="stats-icon stats-income">
                    <i class="bi bi-arrow-down-circle"></i>
                </div>
                <div class="stats-label">إجمالي المستلم</div>
                <div class="stats-value h4 mb-0">
                    {{ number_format($totalCredit ?? 0, 2) }} {{ trans('global.SAR') }}
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-icon stats-expense">
                    <i class="bi bi-arrow-up-circle"></i>
                </div>
                <div class="stats-label">إجمالي المصروف</div>
                <div class="stats-value h4 mb-0">
                    {{ number_format($totalDebit ?? 0, 2) }} {{ trans('global.SAR') }}
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-icon stats-total">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stats-label">عدد العمليات</div>
                <div class="stats-value h4 mb-0">{{ $transactionsCount ?? 0 }}</div>
            </div>
        </div>

        <!-- المبالغ غير المسددة -->
        @if($totalUnpaid > 0)
        <div class="unpaid-section">
            <div class="unpaid-card">
                <div class="unpaid-header">
                    <i class="bi bi-exclamation-triangle"></i>
                    <h5 class="mb-0">المبالغ غير المسددة</h5>
                </div>
                <div class="unpaid-total">
                    إجمالي المبلغ غير المسدد: {{ number_format($totalUnpaid, 2) }} {{ trans('global.SAR') }}
                </div>
                <div class="unpaid-list">
                    @foreach($dueDates as $payment)
                    <div class="unpaid-item {{ $payment['is_overdue'] ? 'overdue' : '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold mb-2">{{ number_format($payment['amount'], 2) }} {{ trans('global.SAR') }}</div>
                                <div class="text-muted">تاريخ الاستحقاق: {{ $payment['due_date']->format('Y-m-d') }}</div>
                            </div>
                            <div class="due-date-badge {{ $payment['is_overdue'] ? 'overdue' : '' }}">
                                @if($payment['is_overdue'])
                                    متأخر
                                @else
                                    متبقي {{ $payment['days_remaining'] }} يوم
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Transactions List -->
        <div class="transactions-container">
            <div class="p-4 border-bottom">
                <h5 class="mb-0">سجل المعاملات</h5>
            </div>

            @if(isset($transactions) && $transactions->count() > 0)
                @foreach($transactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-icon {{ $transaction->type == 'credit' ? 'stats-income' : 'stats-expense' }}">
                            <i class="bi {{ $transaction->type == 'credit' ? 'bi-arrow-down' : 'bi-arrow-up' }}"></i>
                        </div>
                        <div class="transaction-details">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-1">{{ $transaction->description }}</h6>
                                <div class="transaction-amount {{ $transaction->type == 'credit' ? 'credit-amount' : 'debit-amount' }}">
                                    {{ $transaction->type == 'credit' ? '+' : '-' }}
                                    {{ number_format($transaction->amount, 2) }} {{ trans('global.SAR') }}
                                </div>
                            </div>
                            <div class="text-muted small">
                                {{ $transaction->created_at->format('Y-m-d H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="p-4">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-wallet2"></i>
                    <h5>لا توجد معاملات</h5>
                    <p class="text-muted">لم يتم تسجيل أي معاملات في محفظتك حتى الآن</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

