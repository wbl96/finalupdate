<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ConfirmOrderRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductsDetailUser;
use App\Models\RfqRequest;
use App\Notifications\RFQNotification;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OrdersController_Copy extends Controller
{
    public function index()
    {
        return view('store.orders.list');
    }

    public function getOrders()
    {
        // get orders
        $orders = Auth::user()->orders()->orderByDesc('id')->get();
        // Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['status', 'total_items', 'created_at', 'controls'] : $columns;
        // return data into datatables
        return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('total_price', function ($order) {
                return $order->total_price . ' ' . trans('global.SAR');
            })
            ->addColumn('total_items', function ($order) {
                return $order->items->count();
            })
            ->addColumn('status', function ($order) {
                return trans('orders.' . $order->status);
            })
            ->addColumn('controls', function ($order) {
                $btn = '<a href="' . route('store.orders.show', [$order]) . '" class="btn btn-import btn-sm"><i class="fa fa-eye"></i></a>';
                return $btn ?? '';
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    public function confirm(ConfirmOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            // create a new order
            $order = Order::updateOrCreate([
                'cart_id' => Auth::user()->cart->id,
                'store_id' => Auth::id()
            ]);

            // update cart
            Auth::user()->cart->update([
                'rfq_requests_status' => 'close',
            ]);

            // get validated data
            $validated = $request->validated();
            // loop on order items to push it into order
            foreach ($validated['rfq_id'] as $index => $rfqId) {
                $rfq = RfqRequest::find($rfqId);
                $product_id = $rfq->cartItem->detailKey->detail->product_id;

                $order->items()->updateOrCreate([
                    'supplier_id' => $rfq->supplier_id,
                    'product_id' => $product_id,
                    'quantity' => $rfq->qty,
                    'price' => $rfq->proposed_price
                ]);

                $rfq->update([
                    'status' => 'approved'
                ]);
            }

            // update order total price
            $order->update([
                'total_price' => $order->items()->sum('price')
            ]);
            DB::commit();
            return back()->with('success', trans('orders.order confirmed'));
        } catch (\Exception $e) {
            Log::alert($e);
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function requestForQuotations(Cart $cart)
    {
        // update rfq requests status
        $cart->update(['rfq_requests_status' => 'open']);

        // get details keys ids
        $cartItems = $cart->items;

        // loop on keys id
        foreach ($cartItems as $key => $item) {
            // get all suppliers with cart items
            $details = ProductsDetailUser::where('detail_key_id', $item->detail_key_id)
                ->where('min_order_qty', '<', $item->qty)
                ->where('qty', '>', 0)
                ->with('supplier')
                ->get();

            foreach ($details as $key => $detail) {
                $supplier = $detail->supplier;
                $supplier->notify(new RFQNotification(Auth::user()->name));
            }
        }
        return back()->with('success', trans('orders.rfq requests open'));
    }

    public function show(Order $order)
    {
        return view('store.orders.show', compact('order'));
    }

    public function uploadReceipt(Request $request, Order $order)
    {
        $request->validate([
            'bank_transfer_payment' => 'required'
        ]);

        // check product image
        if ($request->hasFile('bank_transfer_payment')) {
            if ($order->image && Storage::disk('public')->exists($order->image)) {
                Storage::disk('public')->delete($order->image);
            }
            // get file
            $file = $request->file('bank_transfer_payment');
            // save file into products folder
            $path = $file->store('paymentReceipt', 'public');
            // update image path in DB
            $order->update([
                'payment_receipt' => $path,
            ]);
        }

        return back()->with('success', trans('global.updated'));
    }
}


// $credential = new ServiceAccountCredentials(
//     "https://www.googleapis.com/auth/firebase.messaging",
//     json_decode(file_get_contents(base_path('wbl-web-firebase-adminsdk-lptzi-426e141d98.json')), true)
// );

// $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

// $ch = curl_init("https://fcm.googleapis.com/v1/projects/wbl-web/messages:send");
// curl_setopt($ch, CURLOPT_HTTPHEADER, [
//     'Content-Type: application/json',
//     'Authorization: Bearer ' . $token['access_token']
// ]);

// curl_setopt($ch, CURLOPT_POSTFIELDS, '{
//     "message": {
//       "token": "fo-uEDzbD_LkSRQbwSoTjd:APA91bEQC_iDmmaeVENca5qjShX4hFoJvR8cciIVjQ2KiewGFP8mGNsB0gp9LYBgttCQosVYd0ljEhhHGu5Eb7OU8UOJv7gKu-CtJtYeuhATBj1zd3-UHTM",
//       "notification": {
//         "title": "Background Message Title",
//         "body": "Background message body",
//         "image": "https://cdn.shopify.com/s/files/1/1061/1924/files/Sunglasses_Emoji.png?2976903553660223024"
//       },
//       "webpush": {
//         "fcm_options": {
//           "link": "https://google.com"
//         }
//       }
//     }
//   }');

// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");

// $response = curl_exec($ch);

// curl_close($ch);

// dd($response);
