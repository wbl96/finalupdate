<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierFromStoreRequest extends FormRequest
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
            'image' => 'sometimes|nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|min:5',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'description' => 'nullable|string|max:255',
        ];
    }
}
