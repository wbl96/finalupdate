<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory, TimestampsTrait;

    protected $fillable = [
        'rfq_request_id',
        'detail_key_id',
        'supplier_id',
        'order_id',
        'quantity',
        'price'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rfqRequst(): BelongsTo
    {
        return $this->belongsTo(RfqRequest::class);
    }

    public function detailKey(): BelongsTo
    {
        return $this->belongsTo(ProductsDetailsKey::class, 'detail_key_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return !is_null($value) ? $this->formatDate($value) : null;
    }
}
