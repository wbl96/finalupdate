<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\Policies\UpdatePolicyRequest;
use App\Http\Resources\SupplierPolicyResource;
use App\Interfaces\Suppliers\PolicyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoliciesController extends Controller
{
    private PolicyRepositoryInterface $policyRepositoryInterface;

    public function __construct(PolicyRepositoryInterface $policyRepositoryInterface)
    {
        $this->policyRepositoryInterface = $policyRepositoryInterface;
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $policy = $this->policyRepositoryInterface->get();
        return ApiResponseClass::sendResponse(new SupplierPolicyResource($policy));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePolicyRequest $request)
    {
        DB::beginTransaction();
        try {
            // prepare data to store it
            $data = [
                'policy' => $request->policy
            ];
            // store policy data
            $policy = $this->policyRepositoryInterface->update($data);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(trans('global.updated'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }
}
