<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\Products\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SupplierProductsResource;
use App\Interfaces\Suppliers\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productRepositoryInterface->index();

        // dd($data);
        return ApiResponseClass::sendResponse(SupplierProductsResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // prepare data to store it
        $data = [
            'product_id' => $request->product_id,
            'details_key_id' => $request->details_key_id,
            'min_order_qty' => $request->min_order_qty,
            'qty' => $request->qty,
        ];

        // start DB transaction
        DB::beginTransaction();
        try {
            // store product data
            $this->productRepositoryInterface->store($data);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('products.inserted'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get product by id
        $product = $this->productRepositoryInterface->getById($id);
        if ($product)
            return ApiResponseClass::sendResponse(new SupplierProductsResource($product));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // prepare data to store it
        $data = [
            'details_key_id' => $request->details_key_id,
            'min_order_qty' => $request->min_order_qty,
            'qty' => $request->qty,
        ];

        // start DB transaction
        DB::beginTransaction();
        try {
            // store product data
            $this->productRepositoryInterface->update($data, $id);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(trans('global.updated'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            $this->productRepositoryInterface->delete($id);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(trans('global.deleted'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    // public function ajaxGetProducts()
    // {
    //     return Product::when(request()->get('q'), function ($q) {
    //         $q->where('name_ar', 'like', '%' . request()->get('q') . '%')
    //             ->orWhere('name_en', 'like', '%' . request()->get('q') . '%');
    //     })
    //         ->where('status', 'active')
    //         ->with('category', 'subcategory', 'detail.keys')
    //         ->orderBy('name_ar')->paginate(20, ['*'], 'page', request()->get('page'));
    // }
}
