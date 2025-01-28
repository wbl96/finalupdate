<?php

namespace App\Http\Requests\Admin\Services;

use App\Enums\Admin\PermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::SERVICES_ADD->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|min:8|unique:services',
            'name_en' => 'required|string|min:8|unique:services',
            'status' => ['required', Rule::in(['certified', 'not certified'])],
        ];
    }
}
