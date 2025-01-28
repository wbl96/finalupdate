<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            "product" => new CartItemProductResource($this->whenLoaded('detailKey')),
            "quantity" => $this->qty,
            // "product" => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
