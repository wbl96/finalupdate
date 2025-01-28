<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = $request->input('get') ?? null;

        if ($type) {
            return [
                'id' => $this->id,
                'image' => $this->image ? asset('storage/' . $this->image) : asset('storage/default.webp'),
                'name_ar' => $this->name_ar,
                'name_en' => $this->name_en,
                'sku' => $this->sku,
                'supplier' => new SupplierResource($this->whenLoaded('supplier')),
                'category' => $this->category,
                'sub_category' => $this->subcategory,
                'description' => $this->description,
                'status' => $this->status,
                'details' => new ProductDetailsResource($this->detail),
            ];
        }

        return [
            'product_supplier_id' => $this->id,
            'id' => $this->product->id,
            'image' => $this->product->image ? asset('storage/' . $this->product->image) : asset('storage/default.webp'),
            'name_ar' => $this->product->name_ar,
            'name_en' => $this->product->name_en,
            'sku' =>  $this->product->sku,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'category' => $this->product->category,
            'sub_category' => $this->product->subcategory,
            'description' => $this->product->description,
            'status' => $this->product->status,
            'details' => new  SupplierProductsDetailsResource($this->product->detail),
        ];
    }
}
