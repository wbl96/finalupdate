<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RFQResource extends JsonResource
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
            'supplier' => $this->supplier_id,
            'cart_item_id' => $this->cart_item_id,
            'detail_key_id' => $this->detail_key_id,
            'product' => new ProductResource($this->detailKey->detail->product),
            'message' => $this->message,
            'proposed_price' => $this->proposed_price,
            'qty' => $this->qty,
            'status' => $this->status,
        ];
    }
}
