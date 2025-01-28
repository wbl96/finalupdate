<?php

namespace App\Http\Requests\Admin\Users;

use App\Enums\admin\PermissionsEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::USERS_UPDATE->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // get id
        $user = $this->route('user') ?? $this->route('store');
        // get target table
        $table = $this->route()->hasParameter('store') ? 'stores' : 'users';
        // rules
        return [
            'name' => 'required|string|min:5',
            'email' => ['required', 'email', Rule::unique($table, 'email')->ignore($user)],
            'mobile' => ['required', 'string', Rule::unique($table, 'mobile')->ignore($user)],
            'type' => ['required', Rule::in(['supplier', 'store'])],
            'lat' => 'required|string',
            'lng' => 'required|string'
        ];
    }
}
