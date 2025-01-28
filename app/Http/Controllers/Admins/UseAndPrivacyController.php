<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteContents\UseAndPrivacy\UpdateRequest;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UseAndPrivacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policy = SiteContent::where('section', 'privacy_policy')->first();
        $policyContent = $policy->content;
        return view('admin.site_content.use_privacy', compact('policy', 'policyContent'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            // get terms
            $terms = SiteContent::where('id', $id)->update($request->validated());
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
}
