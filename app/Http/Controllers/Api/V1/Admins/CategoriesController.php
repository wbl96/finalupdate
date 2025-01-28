<?php

namespace App\Http\Controllers\Api\V1\Admins;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreCategoriesRequest;
use App\Http\Requests\Admin\Categories\UpdateCategoriesRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Interfaces\Admins\ProductCategoriesRepositoryInterface;
use App\Models\ProductsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    private ProductCategoriesRepositoryInterface $productCategoriesRepositoryInterface;

    public function __construct(ProductCategoriesRepositoryInterface $productCategoriesRepositoryInterface)
    {
        $this->productCategoriesRepositoryInterface = $productCategoriesRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productCategoriesRepositoryInterface->index();
        return ApiResponseClass::sendResponse(ProductCategoryResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriesRequest $request)
    {
        // prepare data to store it
        $categoryData = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ];
        DB::beginTransaction();
        try {
            // store category data
            $category = $this->productCategoriesRepositoryInterface->store($categoryData);
            // check if subcategories was added
            if ($request->validated('subcategories')) {
                foreach ($request->subcategories as $subcategory) {
                    $category->subCategories()->updateOrCreate($subcategory);
                }
            }
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('global.inserted'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get category by id
        $category = $this->productCategoriesRepositoryInterface->getById($id);
        if ($category)
            return ApiResponseClass::sendResponse(new ProductCategoryResource($category));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriesRequest $request, string $id)
    {
        // prepare data to store it
        $categoryData = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ];
        DB::beginTransaction();
        try {
            $category = ProductsCategory::where('id', $id)->first();
            // store category data
            $this->productCategoriesRepositoryInterface->update($categoryData, $id);
            // check if subcategories was added
            if ($request->validated('subcategories')) {
                // // get new ids
                // $subCatIds = $request->validated('subcategories')->pluck('id')->toArray();
                // DB::table('products_sub_categories')->whereNotIn('id', $subCatIds)->delete();
                // loop on sub categories
                foreach ($request->subcategories as $subcategory) {
                    $category->subCategories()->updateOrCreate(['id' => $subcategory['id'] ?? null], $subcategory);
                }
            }
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('global.updated'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
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
            $this->productCategoriesRepositoryInterface->delete($id);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(trans('global.deleted'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }
}
