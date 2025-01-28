<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ConfirmOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->cart->items()->count() > 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'product_id' => 'required|array',
            // 'quantity' => 'required|array',
            // 'supplier_id' => 'required|exists:users,id'

            'rfq_id' => 'required|array',
            'rfq_id.*' => 'required|string',
        ];
    }
}
