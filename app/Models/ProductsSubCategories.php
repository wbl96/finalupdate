<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class ProductsSubCategories extends Model
{
    use HasFactory, TimestampsTrait;

    protected $table = 'products_sub_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name_ar',
        'name_en',
        'admin_id',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductsCategory::class, 'category_id');
    }
    
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'id');
    }

    public function getNameAttribute()
    {
        return (App::getLocale() == 'ar' ? $this->name_ar : $this->name_en) ?? '-';
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
