<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\AddCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function show()
    {
        $pending = Cart::query()
            ->where('rfq_requests_status', 'pending')
            ->where('store_id', auth()->id())
            ->with('items', function ($q) {
                $q->select([
                    'id',
                    'cart_id',
                    'detail_key_id',
                    'qty',
                ])->with('detailKey', function ($q) {
                    $q->select([
                        'id',
                        'products_detail_id',
                        'key',
                    ])->with('detail', function ($q) {
                        $q->select([
                            'id',
                            'product_id',
                            'name',
                        ])->with('product', function ($q) {
                            $q->select([
                                'id',
                                'image',
                                'name_ar',
                                'name_en',
                                'sku',
                            ]);
                        });
                    });
                });
            })
            ->select([
                'id',
                'rfq_requests_status'
            ])
            ->first();

        $close = Cart::query()
            ->where('rfq_requests_status', 'close')
            ->where('store_id', auth()->id())
            ->with('items', function ($q) {
                $q->select([
                    'id',
                    'cart_id',
                    'detail_key_id',
                    'qty',
                ])->with('detailKey', function ($q) {
                    $q->select([
                        'id',
                        'products_detail_id',
                        'key',
                    ])->with('detail', function ($q) {
                        $q->select([
                            'id',
                            'product_id',
                            'name',
                        ])->with('product', function ($q) {
                            $q->select([
                                'id',
                                'image',
                                'name_ar',
                                'name_en',
                                'sku',
                            ]);
                        });
                    });
                });
            })
            ->select([
                'id',
                'rfq_requests_status'
            ])
            ->first();

        return view('store.cart', [
            'pending' => $pending->items ?? null,
            'close' => $close->items ?? null
        ]);
    }

    public function store(AddCartItemRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            // Get the current user's cart
            $cart = Auth::user()->cart()->where('rfq_requests_status', 'pending')->first() ?? Cart::create([
                'store_id' => Auth::id(),
            ]);

            // cart item
            $cartItem = [
                'detail_key_id' => $request->detail_key_id,
                'qty' => $request->qty,
            ];

            // check if product exists in cart
            $existingItem = $cart->items()->where('detail_key_id', $request->detail_key_id)->first();
            if ($existingItem) {
                // Update the quantity if the product already exists
                $existingItem->update([
                    'qty' => $request->qty,
                ]);
            } else {
                // Add new item to the cart if it doesn't exist
                $cart->items()->create($cartItem);
            }
            // update cart
            $cart->update([
                'total_items' => $cart->items()->count()
            ]);
            DB::commit();
            return response(['success'   => true, 'data' => trans('orders.product inserted to cart')]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::alert($e->getMessage());
            return response(['success'   => false, 'data' => $e->getMessage()]);
        }
    }

    public function remove($id)
    {
        DB::beginTransaction();
        try {
            // remove product from cart
            CartItem::where('id', $id)->first()->forceDelete();
            DB::commit();
            return  response(['success'   => true, 'data' => trans('orders.product deleted from cart')]);
        } catch (\Exception $e) {
            Log::alert($e);
            return response(['success'   => false, 'data' => $e->getMessage()]);
        }
    }
}
