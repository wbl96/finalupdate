<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RfqRequest;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\RFQNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RFQController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:store');
    }

    public function index()
    {
        $store = Auth::user();
        
        // إنشاء محفظة للمتجر إذا لم تكن موجودة
        $wallet = $store->wallet ?? Wallet::create([
            'store_id' => $store->id,
            'balance' => 0
        ]);

        // جلب عروض الأسعار التي لم تتم الموافقة عليها بعد
        $rfqRequests = RfqRequest::where('store_id', $store->id)
            ->where(function($query) {
                $query->where('status', '!=', 'approved')
                      ->orWhereNull('status');
            })
            ->with(['detailKey.detail.product'])
            ->get();

        $totalAmount = $rfqRequests->sum(function($item) {
            return $item->proposed_price * $item->qty;
        });

        return view('store.rfq', compact('rfqRequests', 'totalAmount', 'wallet'));
    }

    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $store = Auth::user();
                $totalAmount = floatval($request->total_price);
                $useWallet = $request->has('use_wallet');
                $walletAmount = $useWallet ? floatval($request->wallet_amount) : 0;
                $bankAmount = $totalAmount - $walletAmount;
                
                // إذا تم استخدام المحفظة
                if ($useWallet && $walletAmount > 0) {
                    if ($store->wallet->balance < $walletAmount) {
                        throw new \Exception('رصيد المحفظة غير كافٍ');
                    }

                    // خصم المبلغ من المحفظة
                    $store->wallet->decrement('balance', $walletAmount);

                    // تسجيل معاملة المحفظة للخصم
                    WalletTransaction::create([
                        'wallet_id' => $store->wallet->id,
                        'amount' => $walletAmount,
                        'type' => 'credit_used',
                        'description' => 'استخدام رصيد المحفظة في الطلب',
                        'is_paid' => false,
                        'due_date' => now()->addDays(40)
                    ]);
                }

                // حساب وإضافة 5% من مبلغ التحويل البنكي
                if ($bankAmount > 0) {
                    $cashbackAmount = $bankAmount * 0.05;
                    $store->wallet->increment('balance', $cashbackAmount);

                    WalletTransaction::create([
                        'wallet_id' => $store->wallet->id,
                        'amount' => $cashbackAmount,
                        'type' => 'credit',
                        'description' => 'إضافة 5% من قيمة التحويل البنكي',
                        'is_paid' => true
                    ]);
                }

                // إنشاء الطلب - تصحيح اسم العمود
                $order = Order::create([
                    'store_id' => $store->id,
                    'total_price' => $totalAmount, // تغيير من total_amount إلى total_price
                    'status' => 'pending'
                ]);

                // معالجة الملف المرفق
                if ($request->hasFile('transfer_receipt')) {
                    $file = $request->file('transfer_receipt');
                    $path = $file->store('receipts', 'public');
                    $order->update(['payment_receipt' => $path]);
                }

                // تحديث عروض الأسعار
                foreach ($request->id as $rfqId) {
                    $rfq = RfqRequest::findOrFail($rfqId);
                    $rfq->update([
                        'status' => 'approved',
                        'order_id' => $order->id
                    ]);

                    // إنشاء order_item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'rfq_request_id' => $rfq->id,
                        'detail_key_id' => $rfq->detail_key_id,
                        'supplier_id' => $rfq->supplier_id,
                        'quantity' => $rfq->qty,
                        'price' => $rfq->proposed_price
                    ]);
                }

                // تحديث إجمالي الطلب
                $order->update([
                    'total_price' => $order->items->sum(function($item) {
                        return $item->price * $item->quantity;
                    })
                ]);
            });

            return redirect()->route('store.orders.index')
                ->with('success', 'تم إنشاء الطلب بنجاح');

        } catch (\Exception $e) {
            \Log::error('Order Creation Error: ' . $e->getMessage());
            return back()
                ->with('error', 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage())
                ->withInput();
        }
    }
}
