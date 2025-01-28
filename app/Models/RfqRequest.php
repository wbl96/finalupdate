<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RfqRequest extends Model
{
    use HasFactory, TimestampsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_item_id',
        'supplier_id',
        'message',
        'proposed_price',
        'qty',
        'status',
        'detail_key_id',
        'store_id',
    ];

    protected static function booted(){
        parent::boot();
        static::creating(function ($model) {
            $model->supplier_id = auth()->id();
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cartItem(): BelongsTo
    {
        return $this->belongsTo(CartItem::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return !is_null($value) ? $this->formatDate($value) : null;
    }

    public function detailKey(): BelongsTo
    {
        return $this->belongsTo(ProductsDetailsKey::class, 'detail_key_id', 'id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
