<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierCollectionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'payment_amount' => $this->payment_amount,
            'data' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'store_name' => $this->invoice->store->name,
            'remaining_amount' => $this->remaining_amount,
        ];
    }
}
