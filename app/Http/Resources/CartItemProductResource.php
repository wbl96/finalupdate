<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CartItemProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'detail_key_id' => $this->id,
            'image' => $this->detail->product->image ? asset('storage/' . $this->detail->product->image) : asset('storage/default.webp'),
            'name' => $this->detail->product->name_ar,
            'key' => $this->key,
        ];
    }
}
