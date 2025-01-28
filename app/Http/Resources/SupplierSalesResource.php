<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierSalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'store' => $this->store->name,
            'order_id' => $this->id + 10000,
            'total_price' => $this->total_price,
            'payment_status' => trans('orders.' . $this->payment_status),
            // 'date' => Carbon::parse($this->payment_date)->format('Y-m-d'),
            // 'due_date' =>  Carbon::parse($this->payment_date)->copy()->addDays(35)->format('Y-m-d'),
        ];
    }
}
