<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreCategoriesRequest;
use App\Http\Requests\Admin\Categories\UpdateCategoriesRequest;
use App\Models\ProductsCategory;
use App\Models\ProductsSubCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.categories.list");
    }

    public function getCategories(Request $request)
    {
        // get categories
        $categories = ProductsCategory::withCount(['subCategories'])->get();
        // Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['name_ar', 'name_en', 'sub_categories_count', 'admin', 'controls'] : $columns;
        // return data into datatables
        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('admin', function ($category) {
                return $category->admin->name ?? '-';
            })
            ->addColumn('controls', function ($category) {
                // button
                $btn = '<button class="btn btn-sm btn-import text-white m-1" onclick="showEdit(\'' . route('admin.categories.edit', [$category]) . '\')"><i class="fa fa-eye"></i>&nbsp;' . trans('global.edit') . '</button>';
                $btn .= '<button class="btn btn-sm btn-danger text-white m-1" onclick="_delete(\'' . $category->id . '\', \'' . $category->name_ar . '\')"><i class="fa fa-trash-alt"></i>&nbsp;' . trans('global.delete') . '</button>';
                return $btn;
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriesRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // get category validated data
            $validatedData = Arr::except($request->validated(), 'subcategories');
            // create new category
            $category = ProductsCategory::create($validatedData);
            // check if subcategories was added
            if ($request->validated('subcategories')) {
                foreach ($request->subcategories as $subcategory) {
                    $category->subcategories()->updateOrCreate($subcategory);
                }
            }
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('categories.inserted'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductsCategory $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductsCategory $category)
    {
        // check request type
        if (request()->json()) {
            return view('admin.categories.edit_modal', compact('category'));
        }
        // return view with category data
        return response()->json([
            'success' => false
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriesRequest $request, ProductsCategory $category)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = Arr::except($request->validated(), 'subcategories');
            // update category
            $category->update($data);
            // check if subcategories was added
            if ($request->validated('subcategories')) {
                foreach ($request->subcategories as $subCategoryData) {
                    if (!empty($subCategoryData['name_ar']) && !empty($subCategoryData['name_en'])) {
                        if (isset($subCategoryData['id'])) {
                            $category->subCategories()->where('id', $subCategoryData['id'])->update(Arr::except($subCategoryData, 'id'));
                        } else {
                            $category->subCategories()->create($subCategoryData);
                        }
                    }
                }
            }
            // delete subcategories
            if ($request->filled('deleted_sub_categories')) {
                ProductsSubCategories::whereIn('id', $request->deleted_sub_categories)->delete();
            }
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.updated'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductsCategory $category)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // delete category
            $category->delete();
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.deleted'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function getSubcategories(ProductsCategory $category)
    {
        return $category->subCategories()->select('id', 'name_ar', 'name_en')->get();
    }
}
