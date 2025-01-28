<?php

namespace App\Http\Requests\Store;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ConfirmRFQRequest extends FormRequest
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
        if ($this->wantsJson()) {
            return [
                'data' => 'required|array',
                'data.*.id' => 'required|exists:rfq_requests,id',
                'data.*.detail_key_id' => 'required|exists:rfq_requests,detail_key_id',
                'data.*.supplier_id' => 'required|exists:rfq_requests,supplier_id',
                'total_price' => 'required|numeric',
            ];
        }
        return [
            'id' => 'array|required',
            'id.*' => 'exists:rfq_requests,id',
            'supplier_id' => 'array|required',
            'supplier_id.*' => 'exists:rfq_requests,supplier_id',
            'detail_key' => 'array|required',
            'detail_key.*' => 'exists:rfq_requests,detail_key_id',
            'transfer_receipt' => 'file|required',
            'total_price' => 'required|numeric',
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
