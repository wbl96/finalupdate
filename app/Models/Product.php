<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Product extends Model
{
    use HasFactory, TimestampsTrait, SoftDeletes;

    private const NEW = 'new';
    private const ACTIVE = 'active';
    private const INACTIVE = 'inactive';

    public static $PRODUCT_STATUS = [
        self::NEW,
        self::ACTIVE,
        self::INACTIVE
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'name_ar',
        'name_en',
        'sku',
        'category_id',
        'sub_category_id',
        'description',
        'status',
        'expected_price',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(ProductsDetail::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_supplier',  'product_id', 'supplier_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductsCategory::class, 'category_id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(ProductsSubCategories::class, 'sub_category_id', 'id');
    }

    // public function getNameAttribute(): string
    // {
    //     return (App::getLocale() == 'ar' ? $this->name_ar : $this->name_en) ?? '-';
    // }

    public function getTotalSoldAttribute()
    {
        return $this->orders()
            ->where('status', 'received')
            ->sum('order_items.quantity');
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->qty - $this->total_sold;
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return !is_null($value) ? $this->formatDate($value) : null;
    }

    public static function new()
    {
        return self::where('status', 'new');
    }

    public static function active()
    {
        return self::where('status', 'active');
    }

    public static function inactive()
    {
        return self::where('status', 'inactive');
    }
}
