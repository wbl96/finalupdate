<?php

namespace App\Http\Requests\Admin\Categories;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCategoriesRequest extends FormRequest
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
        // get target products_categories
        $category = $this->route('category');
        // rules
        return [
            'name_ar' => ['required', 'string', Rule::unique('products_categories', 'name_ar')->ignore($category)],
            'name_en' => ['required', 'string', Rule::unique('products_categories', 'name_en')->ignore($category)],
            'subcategories' => 'nullable|array',
            'subcategories.*.name_ar' => 'required_if:subcategories,!=,null',
            'subcategories.*.name_en' => 'required_if:subcategories,!=,null'
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
