<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RFQSupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart_id' => $this->cart_id,
            'cart_item_id' => $this->id,
            'store_id' => $this->store_id,
            'rfq_requests_status' => $this->rfq_requests_status,
            'product' => [
                'id' => $this->product_id,
                'name_ar' => $this->name_ar,
                'name_en' => $this->name_en,
                'sku' => $this->sku,
                'image' => $this->image ? asset('storage/' . $this->image) : asset('storage/default.webp'),
                'detail' => [
                    'id' => $this->products_detail_id,
                    'detail_name' => $this->variant_name,
                    'detail_key' => $this->variant_key,
                    'detail_key_id' => $this->detail_key_id
                ]
            ],
            'qty' => $this->qty,
            'date' => $this->created_at
        ];
    }
}
