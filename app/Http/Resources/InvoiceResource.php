<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'store' => $this->store,
            'order' => $this->order,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'status' => $this->status,
            'payments'=> PaymentResource::collection($this->payments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
