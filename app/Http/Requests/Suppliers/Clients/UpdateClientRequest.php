<?php

namespace App\Http\Requests\Suppliers\Clients;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
        // get client
        $client = $this->route()->client;
        // rules
        return [
            'image' => 'sometimes|nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => ['required', 'string', Rule::unique('stores', 'email')->ignore($client)],
            'email' => ['required', 'email', Rule::unique('stores', 'email')->ignore($client)],
            'mobile' => ['required', Rule::unique('stores', 'mobile')->ignore($client)],
            'description' => 'nullable|string|max:255',
            'lng' => 'nullable|string',
            'lat' => 'nullable|string',
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
