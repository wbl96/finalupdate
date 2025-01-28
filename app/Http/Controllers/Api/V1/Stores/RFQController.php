<?php

namespace App\Http\Controllers\Api\V1\Stores;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ConfirmRFQRequest;
use App\Http\Requests\Store\UploadPaymentReceiptRequest;
use App\Http\Resources\RFQResource;
use App\Interfaces\Stores\RFQRepositoryInterface;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderReceiptToken;
use App\Notifications\RFQNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RFQController extends Controller
{
    private RFQRepositoryInterface $rfqRepositoryInterface;

    public function __construct(RFQRepositoryInterface $rfqRepositoryInterface)
    {
        $this->rfqRepositoryInterface = $rfqRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = $this->rfqRepositoryInterface->index();
        return ApiResponseClass::sendResponse(RFQResource::collection($orders));
    }

    public function store(ConfirmRFQRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = Arr::except($request->validated(), 'transfer_receipt');

            // store order
            $order = $this->rfqRepositoryInterface->store($data);
            // check order
            if (!$order) {
                DB::rollBack();
                return ApiResponseClass::rollback(null, 'بوجد عدم تطابق في المتغيرات المرسلة!');
            }
            // generate token
            $token = Str::random(64);
            // save order token
            OrderReceiptToken::create([
                'order_id' => $order->id,
                'token' => $token
            ]);
            // get admins
            $admins = Admin::query()->where('status', 'active')->get();
            // koop on admins to notify them
            foreach ($admins as $admin) {
                $admin->notify(new RFQNotification(Auth::user()->name));
                if ($admin->fcm_token)
                    $this->pushFCMNotifaication($admin->fcm_token, 'توجد حوالة جديدة', 'توجد طلبات جديدة');
            }
            // commit transaction
            DB::commit();
            return ApiResponseClass::sendResponse([
                "token" => $token
            ], "تم ارسال الطلب وسيتم ابلاغكم بحالة وجود تحديثات");
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return ApiResponseClass::rollback($e);
        }
    }

    public function uploadReceipt(UploadPaymentReceiptRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // get order id
            $orderToken = OrderReceiptToken::where('token', $request->token)->first();

            // get transfer receipt file
            $file = $request->file('transfer_receipt');
            // save file into products folder
            $path = $file->store('paymentReceipt', 'public');

            // update order payment receipt 
            Order::where('id', $orderToken->order_id)->update(['payment_receipt' => $path]);

            // delete token
            OrderReceiptToken::where('token', $request->token)->delete();

            // commit transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, "تم ارفاق ايصال الدفع بنجاح!");
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return ApiResponseClass::rollback($e);
        }
    }
}
