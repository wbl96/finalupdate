<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'supplier' => $this->whenLoaded('supplier'),
            'store' => $this->store_id,
            'total_price' => $this->total_price,
            'payment_receipt' => $this->payment_receipt ? asset('storage/' . $this->payment_receipt) : null,
            'status' => $this->status,
            'items' => OrderItemsResource::collection($this->items),
            'created_at' => $this->created_at,
        ];
    }
}
