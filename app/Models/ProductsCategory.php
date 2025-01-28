<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class ProductsCategory extends Model
{
    use HasFactory, TimestampsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'admin_id',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
    
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    
    public function subCategories(): HasMany
    {
        return $this->hasMany(ProductsSubCategories::class, 'category_id');
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
