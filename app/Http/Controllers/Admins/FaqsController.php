<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteContents\Faqs\GetFaqsDataRequest;
use App\Http\Requests\Admin\SiteContents\Faqs\StoreFaqRequest;
use App\Http\Requests\Admin\SiteContents\Faqs\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class FaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.site_content.faqs.faqs_list');
    }

    public function getFaqs(GetFaqsDataRequest $request)
    {
        // get faqs
        $faqs = Faq::all();
        // Retrieve requested columns
        $columns = $request->columns ? array_column($request->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['question_ar', 'question_en', 'answer_ar', 'answer_ar', 'created_by', 'controls'] : $columns;
        // return data into datatables
        return DataTables::of($faqs)
            ->addIndexColumn()
            ->addColumn('created_by', function ($faq) {
                return $faq->createdBy->name;
            })
            ->addColumn('controls', function ($faq) {
                // button
                $btn = '<button class="btn btn-sm btn-import text-white m-1" onclick="showEdit(\'' . route('admin.site-content.faq.edit', [$faq]) . '\')"><i class="fa fa-eye"></i>&nbsp;' . trans('global.edit') . '</button>';
                $btn .= '<button class="btn btn-sm btn-danger text-white m-1" onclick="_delete(\'' . $faq->id . '\', \'' . $faq->question_ar . '\')"><i class="fa fa-trash-alt"></i>&nbsp;' . trans('global.delete') . '</button>';
                return $btn;
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaqRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // create new question
            $question = Faq::create($request->validated());
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.inserted'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Edit the specified resource in storage.
     */
    public function edit(string $id)
    {
        // get faq
        $faq = Faq::findOrFail($id);
        if (request()->ajax()) {
            return view('admin.site_content.faqs.edit_faq_modal', compact('faq'));
        }
        // return view with faq data
        return response()->json([
            'success' => false
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaqRequest $request, string $id)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = $request->validated();
            // update faq
            $faq = Faq::findOrFail($id)->update($data);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // delete faq
            Faq::findOrFail($id)->delete();
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.deleted'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }
}
