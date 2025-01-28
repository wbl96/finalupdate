<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Interfaces\Suppliers\ProductCategoryRepositoryInterface;
use Illuminate\Http\Request;

class ProductsCategoriesController extends Controller
{
    private ProductCategoryRepositoryInterface $productCategoryRepositoryInterface;

    public function __construct(ProductCategoryRepositoryInterface $productCategoryRepositoryInterface)
    {
        $this->productCategoryRepositoryInterface = $productCategoryRepositoryInterface;
    }

    public function index()
    {
        $categories = $this->productCategoryRepositoryInterface->index();
        return ApiResponseClass::sendResponse(ProductCategoryResource::collection($categories));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productCategory = $this->productCategoryRepositoryInterface->getById($id);
        if ($productCategory)
            return ApiResponseClass::sendResponse(new ProductCategoryResource($productCategory));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }
}
