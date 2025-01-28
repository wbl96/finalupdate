<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get search value
        $search = $request->input('search');
        // get suppliers products
        $products = Product::when($search, function ($query, $search) {
                return $query->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%");
            })
            ->with('detail', function($q){
                $q->with('keys', function($q){
                    $q->select([
                        'id',
                        'products_detail_id',
                        'key',
                    ]);
                })
                ->select([
                    'products_details.id',
                    'product_id',
                    'type',
                    'name',
                ]);
            })
            ->where('status', 'active')
            ->orderByDesc('id')
            ->paginate(12);

        // return products with view
        return view('store.index', get_defined_vars());
    }

    public function updateFCM(){
        Store::query()->where('id', auth()->id() ?? null)->update([
            'fcm_token' => request('fcm_token')
        ]);
    }
}
