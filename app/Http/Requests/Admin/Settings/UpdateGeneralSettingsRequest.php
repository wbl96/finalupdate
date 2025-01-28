<?php

namespace App\Http\Requests\Admin\Settings;

use App\Models\Setting;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateGeneralSettingsRequest extends FormRequest
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
        // get current logo
        $currentLogo = Setting::where('key', 'site_logo')->first();

        return [
            'android_url' => 'sometimes|required|url:http,https',
            'android_version' => 'sometimes|required|string',
            'ios_url' => 'sometimes|required|url:http,https',
            'ios_version' => 'sometimes|required|string',
            'maintenance' => 'sometimes|required|in:active,inactive',
            'keywords' => 'sometimes|required|string',
            'google_tag_head' => 'sometimes|required',
            'google_tag_body' => 'sometimes|required',
            'site_logo' => ($currentLogo ? 'nullable' : 'sometimes|required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_name_ar' => 'sometimes|required|string',
            'site_name_en' => 'sometimes|required|string',
        ];
    }

    public function attributes()
    {
        return [
            'android_url' => trans('settings.android url'),
            'android_version' => trans('settings.android version'),
            'ios_url' => trans('settings.ios url'),
            'ios_version' => trans('settings.ios version'),
            'maintenance' => trans('settings.maintenance'),
            'keywords' => trans('settings.keywords'),
            'site_logo' => trans('settings.site logo'),
            'site_name_ar' => trans('settings.site name.ar'),
            'site_name_en' => trans('settings.site name.en'),
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
