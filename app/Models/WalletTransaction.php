<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'description',
        'order_id',
        'is_paid',
        'due_date'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'due_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the wallet that owns the transaction.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the order associated with the transaction.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes للبحث عن المعاملات
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false)
                    ->where('type', 'credit_used');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('is_paid', false)
                    ->where('type', 'credit_used');
    }

    // Helper methods
    public function isDue()
    {
        return $this->due_date && $this->due_date < now();
    }

    public function daysUntilDue()
    {
        if (!$this->due_date) return null;
        return now()->diffInDays($this->due_date, false);
    }
} 