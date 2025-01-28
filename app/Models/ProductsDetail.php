<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ProductsDetail extends Model
{
    use HasFactory, TimestampsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'type',
        'name',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function keys(): HasMany
    {
        return $this->hasMany(ProductsDetailsKey::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return !is_null($value) ? $this->formatDate($value) : null;
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'products_detail_user', 'products_variant_id', 'supplier_id')
            ->withTimesstamps()
            ->withPivot([
                'min_order_qty',
                'max_order_qty',
                'qty',
            ])
            ->with('product');
    }
}
