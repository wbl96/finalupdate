<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory, TimestampsTrait;

    protected $fillable = [
        'cart_id',
        'detail_key_id',
        'qty',
    ];

    public function detail(): BelongsTo
    {
        return $this->belongsTo(ProductsDetail::class, 'detail_key_id');
    }

    public function detailKey(): BelongsTo
    {
        return $this->belongsTo(ProductsDetailsKey::class, 'detail_key_id', 'id');
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function rfqRequests()
    {
        return $this->hasMany(RfqRequest::class, 'cart_item_id');
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
