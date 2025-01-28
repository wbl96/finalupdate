<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TimestampsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'name',
        'email',
        'password',
        'mobile',
        'description',
        'lng',
        'lat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_supplier', 'supplier_id', 'product_id');
    }

    public function productsDetails(): HasMany
    {
        return $this->hasMany(ProductsDetailUser::class, 'supplier_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'supplier_id');
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_supplier', 'supplier_id', 'store_id');
    }

    public function policy(): HasOne
    {
        return $this->hasOne(SuppliersPolicy::class, 'supplier_id');
    }

    public function rfqRequests()
    {
        return $this->hasMany(RfqRequest::class, 'supplier_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return !is_null($value) ? $this->formatDate($value) : null;
    }

    public function product_variants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'products_detail_user', 'detail_key_id', 'supplier_id')
            ->withTimestamps()
            ->withPivot([
                'min_order_qty',
                'qty',
            ])
            ->with('product');
    }
}
