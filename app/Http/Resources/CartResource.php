<?php

namespace App\Http\Resources;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "total_quantity" => (int) $this->items()->sum('qty'),
            "total_items" => $this->total_items,
            "items" => CartItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
