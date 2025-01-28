<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\Products\StoreProductRequest;
use App\Http\Requests\Suppliers\Products\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductsCategory;
use App\Models\ProductsDetail;
use App\Models\ProductsDetailUser;
use App\Models\ProductSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subTitle = trans('products.list');
        $categories = ProductsCategory::all();
        return view('supplier.products.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subTitle = trans('products.add new');
        $products = Product::query()->where('status', 'active')->select([
            'id',
            'name_' . config('app.locale') . ' as name',
            'description',
        ])
        ->orderBy('name')
        ->get();
        return view('supplier.products.create', compact('subTitle', 'products'));
    }

    public function getProducts()
    {
        // get products
        $products = ProductSupplier::query()
            ->where('supplier_id', Auth::id())
            ->with('supplier', 'product')->orderByDesc('id')->get();
        // Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['image', 'name_ar', 'name_en', 'category', 'price', 'qty', 'controls'] : $columns;
        // return data into datatables
        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('image', function ($product) {
                return $product->product->image ? '<img class="img" src="' . Storage::url($product->product->image) . '" width="50" height="50" />' : '<i class="bi bi-x-circle-fill text-danger"></i>';
            })
            ->addColumn('name_ar', function ($prod) {
                return $prod->product->name_ar;
            })
            ->addColumn('name_en', function ($prod) {
                return $prod->product->name_en;
            })
            ->addColumn('category', function ($prod) {
                return $prod->product->category->name;
            })
            ->addColumn('subcategory', function ($prod) {
                return $prod->product->subcategory->name ?? '-';
            })
            ->addColumn('status', function ($prod) {
                return trans('global.' . $prod->status);
            })
            ->addColumn('controls', function ($prod) {
                $btn = '<a href="' . route('supplier.products.edit', [$prod->product]) . '" class="btn btn-import btn-sm"><i class="fa fa-eye"></i>&nbsp;' . trans('global.edit') . '</a>';
                // $btn = '<button class="btn btn-sm btn-import text-white m-1" onclick="showEdit(\'' . route('supplier.products.edit', [$product]) . '\')"><i class="bi bi-eye"></i>&nbsp;' . trans('global.edit') . '</button>';
                $btn .= '<button class="btn btn-sm btn-danger text-white m-1" onclick="_delete(\'' . $prod->id . '\', \'' . $prod->product->name_ar . '\')"><i class="bi bi-trash"></i>&nbsp;' . trans('global.delete') . '</button>';
                return $btn;
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // create product with supplier
            ProductSupplier::create([
                'supplier_id' => Auth::id(),
                'product_id' => $request->product_id,
                'status' => 'new'
            ]);
            // loop on details keys id
            foreach ($request->detail_key_id as $key => $keyId) {
                if ($request->min_order_qty[$key]) {
                    ProductsDetailUser::updateOrCreate([
                        'supplier_id' => Auth::id(),
                        'detail_key_id' => $keyId,
                    ], [
                        'min_order_qty' => $request->min_order_qty[$key],
                        'qty' => $request->qty[$key],
                    ]);
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
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $subTitle = trans('products.edit with name', ['name' => $product->name_ar]);
        $categories = ProductsCategory::all();
        // // check request type
        // if (request()->json()) {
        //     return view('supplier.products.edit_modal', get_defined_vars());
        // }
        return view('supplier.products.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // start db transaction
        DB::beginTransaction();
        try {
            // loop on details keys id
            foreach ($request->detail_key_id as $key => $keyId) {
                // dd($keyId);
                ProductsDetailUser::updateOrCreate([
                    'supplier_id' => Auth::id(),
                    'detail_key_id' => $keyId,
                ], [
                    'min_order_qty' => $request->min_order_qty[$key],
                    'qty' => $request->qty[$key],
                ]);
            }

            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.updated'));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductSupplier $product)
    {
        // sstart DB transaction
        DB::beginTransaction();
        try {
            // get supplier id
            $supplierId = $product->supplier_id;
            // get product details keys
            $productDetailsKeysIds = $product->product->detail->keys->pluck('id')->toArray();
            // delete product detail user
            ProductsDetailUser::where('supplier_id', $supplierId)
                ->whereIn('detail_key_id', $productDetailsKeysIds)
                ->delete();
            // delete product
            $product->delete();
            // commit DB transaction
            DB::commit();
            // return back with success message
            return redirect()->route('supplier.products.list')->with('success', trans('global.deleted'));
        } catch (\Exception $e) {
            dd($e);
            // rollback BD transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function ajaxGetProducts()
    {
        return Product::when(request()->get('q'), function ($q) {
            $q->where('name_ar', 'like', '%' . request()->get('q') . '%')
                ->orWhere('name_en', 'like', '%' . request()->get('q') . '%');
        })
            ->where('status', 'active')
            ->with('category', 'subcategory', 'detail.keys')
            ->orderBy('name_ar')->paginate(20, ['*'], 'page', request()->get('page'));
    }
}
