<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_amount' => $this->payment_amount,
            'remaining_amount' => $this->remaining_amount,
            'payment_date' => Carbon::parse($this->payment_date)->format('Y-m-d'),
            'created_at' => $this->created_at,
        ];
    }
}
