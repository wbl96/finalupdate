<?php

namespace App\Http\Requests\Admin\Users;

use App\Enums\admin\PermissionsEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user()->can(PermissionsEnum::USERS_ADD->value);
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
            'name' => 'required|string|min:5',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'mobile' => ['required', 'string', Rule::unique('users', 'mobile')],
            'type' => ['required', Rule::in(['supplier', 'store'])],
            'lat' => 'required|string',
            'lng' => 'required|string'
        ];
    }
}
