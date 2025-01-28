<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierStoresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name_ar' => $this->product->name_ar,
            'name_en' => $this->product->name_en,
            'sku' => $this->product->sku,
            'code' => 'PROD-' . ($this->id + 10000),
            'details' => SupplierProductsDetailsKeysResource::collection($this->product->detail->keys),
        ];
    }
}
