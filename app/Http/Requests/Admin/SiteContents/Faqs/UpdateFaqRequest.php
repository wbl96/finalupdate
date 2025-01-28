<?php

namespace App\Http\Requests\Admin\SiteContents\Faqs;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateFaqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // get target faq
        $id = $this->route('faq');
        // rule
        return [
            'question_ar' => ['required', 'string', Rule::unique('faqs', 'question_ar')->ignore($id)],
            'question_en' => ['sometimes', 'nullable', 'string', Rule::unique('faqs', 'question_en')->ignore($id)],
            'answer_ar' => ['required', 'string', Rule::unique('faqs', 'answer_ar')->ignore($id)],
            'answer_en' => ['sometimes', 'nullable', 'string', Rule::unique('faqs', 'answer_en')->ignore($id)],
        ];
    }

    public function attributes()
    {
        return [
            'question_ar' => trans('site_content.question ar'),
            'question_en' => trans('site_content.question en'),
            'answer_ar' => trans('site_content.answer ar'),
            'answer_en' => trans('site_content.answer en'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'Validation errors',
                'data'      => $validator->errors()
            ]));
        }

        parent::failedValidation($validator);
    }
}
