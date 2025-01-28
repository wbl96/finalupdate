<div class="sidebar-card">
    <div class="card-icon">
        <i class="bi bi-wallet2"></i>
    </div>
    <h5 class="card-title">خيارات الدفع</h5>
    <div class="card-content">
        @if($store->hasUnpaidCredit())
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                لا يمكن استخدام رصيد المحفظة حالياً. يوجد مبلغ سابق غير مسدد.
            </div>
        @else
            <div class="wallet-info">
                <div class="info-row">
                    <div class="info-label">الرصيد المتاح في المحفظة</div>
                    <div class="info-value">{{ number_format($store->wallet_balance, 2) }} ر.س</div>
                </div>
                
                @if($store->wallet_balance >= $totalAmount)
                    <div class="payment-option">
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   name="use_wallet_credit" 
                                   id="use_wallet_credit">
                            <label class="form-check-label" for="use_wallet_credit">
                                استخدام رصيد المحفظة للدفع الآجل
                            </label>
                        </div>
                        <div class="credit-terms mt-3 d-none" id="credit_terms">
                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle"></i> شروط الدفع الآجل:</h6>
                                <ul class="mb-0">
                                    <li>سيتم استخدام كامل المبلغ ({{ number_format($totalAmount, 2) }} ر.س) من رصيد محفظتك</li>
                                    <li>يجب سداد المبلغ خلال 40 يوم من تاريخ الطلب</li>
                                    <li>لن يمكنك استخدام رصيد المحفظة مجدداً حتى سداد هذا المبلغ</li>
                                    <li>تاريخ استحقاق السداد: {{ now()->addDays(40)->format('Y-m-d') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        رصيد محفظتك غير كافٍ لتغطية مبلغ الطلب
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@push('script')
<script>
    $('#use_wallet_credit').change(function() {
        $('#credit_terms').toggleClass('d-none', !this.checked);
        $('#bank_transfer_section').toggleClass('d-none', this.checked);
    });
</script>
@endpush 