<?php

namespace App\Http\Controllers\Api\V1\Stores;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Interfaces\Stores\ProductRepositoryInterface;

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
        return ApiResponseClass::sendResponse(ProductResource::collection($data));
    }

    public function getCategoriesProducts(string $category_id) {
        $data = $this->productRepositoryInterface->getCategoriesProducts($category_id);
        // check data
        if ($data)
            return ApiResponseClass::sendResponse(ProductResource::collection($data));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }
}
