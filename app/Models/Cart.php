<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory, TimestampsTrait;

    protected $fillable = [
        'store_id',
        'total_items',
        'rfq_requests_status'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function isRfqRequestsOpen(): bool
    {
        return $this->rfq_requests_status === 'open';
    }

    public function isRfqRequestsPending(): bool
    {
        return $this->rfq_requests_status === 'pending';
    }

    public function isRfqRequestsClose(): bool
    {
        return $this->rfq_requests_status === 'close';
    }

    public function scopeForStatus(Builder $query, string $status): Builder
    {
        return $query->where('rfq_requests_status', 'pending');
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
