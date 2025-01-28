<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, TimestampsTrait;

    protected $fillable = [
        'invoice_id',
        'payment_amount',
        'remaining_amount',
        'payment_date',
        'payment_method'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function scopeForSupplier($query, $supplierId)
    {
        return $query->whereHas('invoice.order', function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        });
    }
    
    public function scopeForStore($query, $storeId)
    {
        return $query->whereHas('invoice.order', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        });
    }

    public function scopeDaily($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function scopeWeekly($query)
    {
        return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function scopeMonthly($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year);
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
