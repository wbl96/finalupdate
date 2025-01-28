<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'android_url' => $this->android_url,
            'android_version' => $this->android_version,
            'ios_url' => $this->ios_url,
            'ios_version' => $this->ios_version,
            'maintenance' => $this->maintenance,
            'keywords' => $this->keywords,
            'google_tag_head' => $this->google_tag_head,
            'google_tag_body' => $this->google_tag_body,
            'site_logo' => $this->site_logo ? asset('storage/' . $this->site_logo) : asset('storage/default.webp'),
            'site_name_ar' => $this->site_name_ar,
            'site_name_en' => $this->site_name_en,
        ];
    }
}
