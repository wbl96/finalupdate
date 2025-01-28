<?php

namespace App\Http\Requests\Admin\SiteContents\Faqs;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFaqRequest extends FormRequest
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
        return [
            'question_ar' => 'required|string|unique:faqs',
            'question_en' => 'sometimes|nullable|string|unique:faqs',
            'answer_ar' => 'required|string|unique:faqs',
            'answer_en' => 'sometimes|nullable|string|unique:faqs',
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
