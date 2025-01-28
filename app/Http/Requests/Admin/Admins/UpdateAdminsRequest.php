<?php

namespace App\Http\Requests\Admin\Admins;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class UpdateAdminsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->ajax();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // get admin id
        $id = $this->route('admin');
        // rules
        return [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('admins')->ignore($id)],
            'department_id' => 'sometimes|nullable',
            'password' => 'sometimes|nullable|confirmed',
            'status'=> 'sometimes|in:active,inactive',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->input('password') === null) {
            $this->request->remove('password');
        }
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
