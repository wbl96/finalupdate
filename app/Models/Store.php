<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\HasApiTokens;

class Store extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TimestampsTrait;

    protected $guard = 'store';

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

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class)->whereIn('rfq_requests_status', ['pending']);
    }

    public function rfq(): HasMany
    {
        return $this->hasMany(RfqRequest::class)->whereIn('status', ['pending']);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_supplier', 'store_id', 'supplier_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'store_id');
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
