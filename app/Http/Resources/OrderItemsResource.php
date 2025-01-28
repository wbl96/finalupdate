<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->detailKey->detail->product;
        $productDetail = $this->detailKey;
        return [
            'id' => $this->id,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'order' => new OrderResource($this->whenLoaded('order')),
            'product' => [
                'id' => $product->id,
                'image' => $product->image ? asset('storage/' . $product->image) : asset('storage/default.webp'),
                'name_ar' => $product->name_ar,
                'name_en' => $product->name_en,
                'sku' => $product->sku,
                'description' => $product->description,
                'detail' => [
                    'id' => $productDetail->id,
                    'key' => $productDetail->key
                ]
            ],
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
