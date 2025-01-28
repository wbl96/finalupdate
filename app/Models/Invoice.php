<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory, TimestampsTrait;

    protected $fillable = [
        'store_id',
        'order_id',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function updateStatus()
    {
        if ($this->remaining_amount == 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0 && $this->remaining_amount > 0) {
            $this->status = 'partially_paid';
        } else {
            $this->status = 'pending';
        }
        // save
        $this->save();
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
