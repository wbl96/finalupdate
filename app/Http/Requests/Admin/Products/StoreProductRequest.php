<?php

namespace App\Http\Requests\Admin\Products;

use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name_ar' => 'required|string|max:255|unique:products',
            'name_en' => 'required|string|max:255|unique:products',
            'sku' => 'required|string|max:255',
            'category_id' => 'required|exists:products_categories,id',
            'sub_category_id' => 'nullable|sometimes|exists:products_sub_categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(",", Product::$PRODUCT_STATUS),
            // 'min_order_qty' => 'required|integer|min:0',
            // 'max_order_qty' => 'required|integer|min:0|gte:min_order_qty',
            // 'qty' => 'required|integer|min:0',
            // 'payment_type' => 'nullable|string|max:255',
            // 'delivery_method' => 'nullable|string|max:255',
            // 'price' => 'required|numeric|min:0',

            'detail' => 'nullable|array',
            'detail.*.type' => 'required_if:details,!=,null|string|max:255',
            'detail.*.name' => 'required_if:details,!=,null|string|max:255',
            'detail.*.key' => 'required_if:details,!=,null|array|max:255',
            'detail.*.key.*' => 'required_if:key,!=,null|string|max:255',
            'expected_price' => 'nullable|numeric|min:0',
            // 'details.*.qty' => 'required_if:details,!=,null|string|max:255'
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
