<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\Admin\Products\StoreProductRequest;
use App\Http\Requests\Admin\Products\UpdateProductRequest;
use App\Models\ProductsCategory;
use App\Models\ProductsDetailsKey;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ProductsDetail;
use App\Models\ProductSupplier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // target title && title
        $targetType = request()->type;
        // check request
        if (request()->ajax()) {
            // get products
            $products = Product::query();
            // check target type
            if (in_array($targetType, ['active', 'inactive'])) {
                $products->where('status', $targetType);
            }
            $products = $products->orderByDesc('id')->get();
            // Retrieve requested columns
            $columns = request()->columns ? array_column(request()->columns, 'data') : [];
            // html columns
            $htmlColumns = empty($columns) ?  ['image', 'name_ar', 'name_en', 'category', 'price', 'qty', 'controls'] : $columns;
            // return data into datatables
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image', function ($product) {
                    return $product->image ? '<img class="img" src="' . Storage::url($product->image) . '" width="50" height="50" />' : '<i class="bi bi-x-circle-fill text-danger"></i>';
                })
                ->addColumn('category', function ($product) {
                    return $product->category->name;
                })
                ->addColumn('subcategory', function ($product) {
                    return $product->subcategory->name ?? '-';
                })
                ->addColumn('status', function ($product) {
                    return trans('global.' . $product->status);
                })
                ->addColumn('controls', function ($product) {
                    $btn = '<a href="' . route('admin.products.edit', [$product]) . '" class="btn btn-import btn-sm"><i class="fa fa-eye"></i>&nbsp;' . trans('global.edit') . '</a>';
                    // $btn = '<button class="btn btn-sm btn-import text-white m-1" onclick="showEdit(\'' . route('admin.products.edit', [$product]) . '\')"><i class="bi bi-eye"></i>&nbsp;' . trans('global.edit') . '</button>';
                    $btn = '<a class="btn btn-sm btn-import text-white m-1" href="' . route('admin.products.edit', [$product]) . '"><i class="bi bi-eye"></i>&nbsp;' . trans('global.edit') . '</a>';
                    $btn .= '<button class="btn btn-sm btn-danger text-white m-1" onclick="_delete(\'' . $product->id . '\', \'' . $product->name_ar . '\')"><i class="bi bi-trash"></i>&nbsp;' . trans('global.delete') . '</button>';
                    return $btn;
                })
                ->rawColumns($htmlColumns)
                ->make(true);
        }

        $title = trans('users.the users') . ' - ' . $targetType;
        $categories = ProductsCategory::all();
        return view('admin.products.index', compact('title', 'targetType', 'categories'));
    }

    public function newRequests()
    {
        // check request
        if (request()->ajax()) {
            // get data
            $data = ProductSupplier::query()->with('supplier', 'product')
                ->where('status', 'new')
                ->orderByDesc('id')
                ->get();

            // Retrieve requested columns
            $columns = request()->columns ? array_column(request()->columns, 'data') : [];
            // html columns
            $htmlColumns = empty($columns) ?  ['image', 'name_ar', 'name_en', 'category', 'price', 'qty', 'controls'] : $columns;
            // return data into datatables
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($request) {
                    return $request->product->image ? '<img class="img" src="' . Storage::url($request->product->image) . '" width="50" height="50" />' : '<i class="bi bi-x-circle-fill text-danger"></i>';
                })
                ->addColumn('name_ar', function ($request) {
                    return $request->product->name_ar;
                })
                ->addColumn('name_en', function ($request) {
                    return $request->product->name_en;
                })
                ->addColumn('category', function ($request) {
                    return $request->product->category->name;
                })
                ->addColumn('subcategory', function ($request) {
                    return $request->product->subcategory->name ?? '-';
                })
                ->addColumn('status', function ($request) {
                    return trans('global.' . $request->status);
                })
                ->addColumn('controls', function ($request) {
                    $btn = '<form action="' . route('admin.products.activate', $request) . '" method="POST">' . csrf_field() . '<button type="submit" class="btn btn-sm btn-import text-white m-1"><i class="bi bi-check-lg"></i>&nbsp;' . trans('global.activate') . '</button></form>';
                    $btn .= '<button class="btn btn-sm btn-danger text-white m-1" onclick="_delete(\'' . $request->product->id . '\', \'' . $request->product->name_ar . '\')"><i class="bi bi-trash"></i>&nbsp;' . trans('global.delete') . '</button>';
                    return $btn;
                })
                ->rawColumns($htmlColumns)
                ->make(true);
        }
        $title = trans('products.request add product');
        $categories = ProductsCategory::all();
        return view('admin.products.requests', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = trans('products.add new');
        $categories = ProductsCategory::all();
        $status = Product::$PRODUCT_STATUS;
        return view('admin.products.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // get validated data
            $validatedData = Arr::except($request->validated(), ['image', 'detail']);
            
            // add expected price if provided
            if ($request->has('expected_price')) {
                $validatedData['expected_price'] = $request->expected_price;
            }
            
            // create new product
            $product = Product::create($validatedData);
            // check product image
            if ($request->hasFile('image')) {
                // get file
                $file = $request->file('image');
                // save file into products folder
                $path = $file->store('productsImages', 'public');
                // update image path in DB
                $product->update([
                    'image' => $path,
                ]);
            }
            // check if detail was set
            if ($request->has('detail')) {
                // get detail
                $details = $request->detail;
                // create new detail
                $detail = $product->detail()->create(Arr::except($details, ['key']));
                $detailKeys = Arr::get($details, 'key');
                // loop on detail keys to insert it into database
                foreach ($detailKeys as $value) {
                    $detail->keys()->create(['key' => $value]);
                }
            }
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('products.inserted'));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $title = trans('products.edit with name', ['name' => $product->name_ar]);
        $categories = ProductsCategory::all();
        $status = Product::$PRODUCT_STATUS;
        return view('admin.products.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // get validated data
            $validatedData = Arr::except($request->validated(), ['image', 'detail']);
            
            // add expected price if provided
            if ($request->has('expected_price')) {
                $validatedData['expected_price'] = $request->expected_price;
            }
            
            // update product
            $product->update($validatedData);
            // check product image
            if ($request->hasFile('image')) {
                // get current image
                $image = $product->image;
                // delete it if exists
                if ($image && Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
                // get file
                $file = $request->file('image');
                // save file into products folder
                $path = $file->store('productsImages', 'public');
                // update image path in DB
                $product->update([
                    'image' => $path,
                ]);
            }
            // check if detail was set
            if ($request->has('detail')) {
                // get detail
                $detail = $request->detail;
                // dd($detail);
                $detailArr = Arr::except($detail, ['key', 'keys_id']);
                // create new detail
                $product->detail()->updateOrCreate(['id' => $detailArr['id'] ?? null], $detailArr);
                $detailKeys = Arr::get($detail, 'key');
                $detailKeysIds = Arr::get($detail, 'keys_id') ?? null;
                // loop on detail keys to insert it into database
                foreach ($detailKeys as $key => $value) {
                    if ($detailKeysIds && $detailKeysIds[$key]) {
                        $product->detail->keys()->where('id', $detailKeysIds[$key])->update(['key' => $value]);
                    } else {
                        $product->detail->keys()->create(['key' => $value]);
                    }
                }
            }
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.updated'));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function activateRequest(ProductSupplier $productSupplier)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            $productSupplier->update(['status' => 'active']);
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.updated'));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function deactivateRequest(ProductSupplier $productSupplier)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            $productSupplier->update(['status' => 'inactive']);
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.updated'));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // get product
            $product = Product::with(['detail.keys' => function ($query) {
                return $query->withCount('orders');
            }])->where('id', $id)->first();
            // get total orders of this product
            $totalOrders = $product->detail->keys->map(function ($keys) {
                return $keys;
            })->sum('orders_count');

            // check total orders
            if ($totalOrders > 0) {
                // soft delete
                $product->delete();
            } else {
                // force delete if no orders
                $product->forceDelete();
            }

            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.deleted'));
        } catch (\Exception $e) {
            dd($e);
            // rollback BD transaction
            DB::rollBack();
            Log::error($e);
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function destroyProductDetailKey(string $id)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            $keysWithOrders = ProductsDetailsKey::where('id', $id)
                ->with(['orders.items' => function ($query) use ($id) {
                    $query->where('detail_key_id', $id);
                }])->first();
            // get total orders count
            $totalOrders = $keysWithOrders->orders?->count() ?? 0;
            // check total orders
            if ($totalOrders > 0) {
                // soft delete
                $keysWithOrders->delete();
            } else {
                // force delete if no orders
                $keysWithOrders->forceDelete();
            }

            // commit DB transaction
            DB::commit();
            // return back with success message
            return response()->json([
                'status' => 'success',
                'message' => trans('global.deleted')
            ]);
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ])->throwResponse();
        }
    }
}
