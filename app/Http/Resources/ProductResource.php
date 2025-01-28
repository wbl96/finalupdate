<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image ? asset('storage/' . $this->image) : asset('storage/default.webp'),
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'sku' => $this->sku,
            'supplier' => new SupplierResource($this->supplier),
            'category' => $this->category,
            'sub_category' => $this->subCategory,
            'description' => $this->description,
            'status' => $this->status,
            'details' => new ProductDetailsResource($this->whenLoaded('detail')),
        ];
    }
}
