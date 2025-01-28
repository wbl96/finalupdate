<?php

namespace App\Http\Controllers\Api\V1\Stores;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\AddCartItemRequest;
use App\Http\Resources\CartResource;
use App\Interfaces\Stores\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private CartRepositoryInterface $cartRepositoryInterface;

    public function __construct(CartRepositoryInterface $cartRepositoryInterface)
    {
        $this->cartRepositoryInterface = $cartRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->cartRepositoryInterface->index();
        return ApiResponseClass::sendResponse(CartResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddCartItemRequest $request)
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
                $this->cartRepositoryInterface->updateItem($existingItem, ['qty' => $request->qty]);
            } else {
                // Add new item to the cart if it doesn't exist
                $this->cartRepositoryInterface->addItem($cart, $cartItem);
            }
            // cart new price and items
            $cartData = [
                'total_items' => $cart->items()->count()
            ];
            // update cart total price & items
            $this->cartRepositoryInterface->updateCart($cart, $cartData);
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('orders.product inserted to cart'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Get the current user's cart
            $cart = Auth::user()->cart;
            // remove product from cart
            $cart->items()->where('id', $id)->first()->delete();
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('orders.product deleted from cart'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }
}
