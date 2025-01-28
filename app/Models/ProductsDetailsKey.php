<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsDetailsKey extends Model
{
    use HasFactory, TimestampsTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'products_detail_id',
        'key',
    ];

    public function detail(): BelongsTo
    {
        return $this->belongsTo(ProductsDetail::class, 'products_detail_id', 'id');
    }

    public function detailUser(): HasOne
    {
        return $this->hasOne(ProductsDetailUser::class, 'detail_key_id', 'id');
    }

    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            OrderItem::class,
            'detail_key_id', // المفتاح الأجنبي في OrderItem
            'id',            // المفتاح الأساسي في Order
            'id',            // المفتاح الأساسي في ProductDetailKey
            'order_id'       // المفتاح الأجنبي في OrderItem
        );
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
