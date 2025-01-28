<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'description'=> $this->description,
            "num_purchases" => $this->orders_count ?? 0,
            'lng' => $this->lng,
            'lat' => $this->lat,
        ];
    }
}
