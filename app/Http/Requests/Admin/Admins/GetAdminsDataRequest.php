<?php

namespace App\Http\Requests\Admin\Admins;

use App\Enums\Admin\PermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class GetAdminsDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->ajax() && $this->user()->can(PermissionsEnum::ADMINS_SHOW->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
