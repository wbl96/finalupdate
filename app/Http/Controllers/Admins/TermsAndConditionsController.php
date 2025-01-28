<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteContents\TermsAndConditions\UpdateRequest;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TermsAndConditionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terms = SiteContent::where('section', 'terms')->first();
        $termsContent = $terms->content;
        return view('admin.site_content.terms_conditions', compact('terms', 'termsContent'));
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
