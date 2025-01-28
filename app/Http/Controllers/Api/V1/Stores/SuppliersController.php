<?php

namespace App\Http\Controllers\Api\V1\Stores;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreSupplierFromStoreRequest;
use App\Http\Resources\SupplierResource;
use App\Interfaces\Stores\SupplierRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuppliersController extends Controller
{
    private SupplierRepositoryInterface $supplierRepositoryInterface;

    public function __construct(SupplierRepositoryInterface $supplierRepositoryInterface)
    {
        $this->supplierRepositoryInterface = $supplierRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->supplierRepositoryInterface->index();
        return ApiResponseClass::sendResponse(SupplierResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierFromStoreRequest $request)
    {
        // prepare data to store it
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ];

        // start DB transaction
        DB::beginTransaction();
        try {
            // generate a temp password
            $data['password'] = Hash::make('123456789');
            // store supplier data
            $supplier = $this->supplierRepositoryInterface->store($data);
            // attach store with supplier
            Auth::user()->suppliers()->syncWithoutDetaching([$supplier->id]);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('users.supplier inserted'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }
}
