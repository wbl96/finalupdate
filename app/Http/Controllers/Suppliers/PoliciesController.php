<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\Policies\StorePolicyRequest;
use App\Http\Requests\Suppliers\Policies\UpdatePolicyRequest;
use App\Models\SuppliersPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PoliciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get user policy
        $policy = Auth::user()->policy ?? null;
        $policyContent = $policy->policy ?? null;
        $formMethod = $policy ? 'put' : 'post';
        // check if auth user has policy or not
        $formAction = $policy ? route('supplier.policies.update', [$policy]) : route('supplier.policies.store');
        return view('supplier.policies.sales_return', compact('formMethod', 'policyContent', 'formAction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePolicyRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // update policy content
            SuppliersPolicy::create($request->validated());
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.inserted'));
        } catch (\Exception $e) {
            // rollback BD transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePolicyRequest $request, SuppliersPolicy $policy)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // update policy content
            $policy->update($request->validated());
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
}
