<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\Clients\StoreClientRequest;
use App\Http\Requests\Suppliers\Clients\UpdateClientRequest;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subTitle = trans('clients.list');
        return view('supplier.clients.index', compact('subTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subTitle = trans('clients.add new');
        return view('supplier.clients.add', compact('subTitle'));
    }

    public function getClients()
    {
        // get clients
        $clients = Auth::user()->stores()->orderByDesc('id')->get();
        // Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['name', 'email', 'mobile', 'num_purchases'] : $columns;
        // return data into datatables
        return DataTables::of($clients)
            ->addIndexColumn()
            ->addColumn('num_purchases', function ($client) {
                $num_purchases = Order::where('supplier_id', Auth::id())
                    ->where('store_id', $client->id)
                    ->whereNotIn('status', ['pending', 'refunded'])
                    ->whereNotIn('payment_status', ['pending', 'refunding'])
                    ->count();
                return $num_purchases;
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = $request->validated();
            // generate a temp password
            $data['password'] = Hash::make('123456789');
            // create a new store
            $store = Store::createOrFirst([
                'email' => $data['email'],
                'mobile' => $data['mobile'],
            ], $data);
            // attach store to this supplier
            Auth::user()->stores()->syncWithoutDetaching($store->id);
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('clients.inserted', ['name' => $store->name]));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }
}
