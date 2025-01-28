<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreSupplierFromStoreRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class SuppliersController extends Controller
{
    public function index()
    {
        return view('store.suppliers.list');
    }

    public function getSuppliers()
    {
        // check request
        if (request()->ajax()) {
            // get users
            $data = Auth::user()->suppliers()->orderByDesc('id')->get();
            // Retrieve requested columns
            $columns = request()->columns ? array_column(request()->columns, 'data') : [];
            // html columns
            $htmlColumns = empty($columns) ?  ['name', 'email', 'mobile', 'num_purchases'] : $columns;
            // return data after format it into datatables
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('num_purchases', function ($supplier) {
                    $num_purchases = Order::where('store_id', Auth::id())
                        ->where('supplier_id', $supplier->id)
                        ->whereNotIn('status', ['pending', 'refunded'])
                        ->whereNotIn('payment_status', ['pending', 'refunding'])
                        ->count();
                    return $num_purchases;
                })
                ->rawColumns($htmlColumns)
                ->make(true);
        }
    }

    public function store(StoreSupplierFromStoreRequest $request)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = $request->validated();
            // generate a temp password
            $data['password'] = Hash::make('123456789');
            // create a new supplier
            $supplier = User::createOrFirst([
                'email' => $data['email'],
                'mobile' => $data['mobile'],
            ], $data);
            // // send a reset password email
            // $this->sendResetPasswordEmail($data['email']);
            // attach store with supplier
            Auth::user()->suppliers()->syncWithoutDetaching([$supplier->id]);
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('users.supplier inserted'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }
}
