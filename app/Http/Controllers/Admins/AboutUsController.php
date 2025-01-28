<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteContents\AboutUsConditions\UpdateRequest;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about = SiteContent::where('section', 'about_us')->first();
        $aboutContent = $about->content;
        return view('admin.site_content.about_us', compact('about', 'aboutContent'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            // get about
            $about = SiteContent::where('id', $id)->update($request->validated());
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
