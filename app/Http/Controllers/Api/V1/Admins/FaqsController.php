<?php

namespace App\Http\Controllers\Api\V1\Admins;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteContents\Faqs\StoreFaqRequest;
use App\Http\Requests\Admin\SiteContents\Faqs\UpdateFaqRequest;
use App\Http\Resources\FaqResource;
use App\Interfaces\Admins\FaqsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqsController extends Controller
{
    private FaqsRepositoryInterface $faqsRepositoryInterface;

    public function __construct(FaqsRepositoryInterface $faqsRepositoryInterface)
    {
        $this->faqsRepositoryInterface = $faqsRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->faqsRepositoryInterface->index();
        return ApiResponseClass::sendResponse(FaqResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaqRequest $request)
    {
        // prepare data to store it
        $data = [
            'question_ar' => $request->question_ar,
            'question_en' => $request->question_en,
            'answer_ar' => $request->answer_ar,
            'answer_en' => $request->answer_en,
        ];

        DB::beginTransaction();
        try {
            // store faq data
            $faq = $this->faqsRepositoryInterface->store($data);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('global.inserted'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get faq by id
        $faqs = $this->faqsRepositoryInterface->getById($id);
        if ($faqs)
            return ApiResponseClass::sendResponse(new FaqResource($faqs));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaqRequest $request, string $id)
    {
        // prepare data to store it
        $data = [
            'question_ar' => $request->question_ar,
            'question_en' => $request->question_en,
            'answer_ar' => $request->answer_ar,
            'answer_en' => $request->answer_en,
        ];

        DB::beginTransaction();
        try {
            // store faq data
            $faq = $this->faqsRepositoryInterface->update($data, $id);
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
             $this->faqsRepositoryInterface->delete($id);
             // commit DB transaction
             DB::commit();
             return ApiResponseClass::sendResponse(trans('global.deleted'));
         } catch (\Exception $ex) {
             return ApiResponseClass::rollback($ex);
         }
    }
}
