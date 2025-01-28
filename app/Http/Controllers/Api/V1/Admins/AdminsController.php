<?php

namespace App\Http\Controllers\Api\V1\Admins;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admins\StoreAdminsRequest;
use App\Http\Resources\AdminResource;
use App\Interfaces\Admins\AdminRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    private AdminRepositoryInterface $adminRepositoryInterface;

    public function __construct(AdminRepositoryInterface $adminRepositoryInterface)
    {
        $this->adminRepositoryInterface = $adminRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->adminRepositoryInterface->index();
        return ApiResponseClass::sendResponse(AdminResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminsRequest $request)
    {
        // prepare data to store it
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department,
        ];

        DB::beginTransaction();
        try {
            // generate a temp password
            $data['password'] = Hash::make('123456789');
            // store admin data
            $admin = $this->adminRepositoryInterface->store($data);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('admins.inserted'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get admin by id
        $admin = $this->adminRepositoryInterface->getById($id);
        if ($admin)
            return ApiResponseClass::sendResponse(new AdminResource($admin));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // prepare data to store it
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department,
        ];

        DB::beginTransaction();
        try {
            // check if password set
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            // store admin data
            $admin = $this->adminRepositoryInterface->update($data, $id);
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
            $this->adminRepositoryInterface->delete($id);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(trans('global.deleted'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }
}
