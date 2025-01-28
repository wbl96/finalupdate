<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StorePurchasesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        //     // 'supplier' => $this->supplier->name,
            'order_id' => $this->id,
            'total_price' => $this->total_price,
            'payment_receipt' => $this->payment_receipt ? asset('storage/' . $this->payment_receipt) : null,
            'status' =>  $this->status,
            'date' => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
