<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, TimestampsTrait;

    private const pending = 'pending';
    private const new = 'new';
    private const dispatched = 'dispatched';
    private const delivered = 'delivered';
    private const reject = 'reject';
    private const canceled = 'canceled';
    private const refunded = 'refunded';

    public static $ORDER_STATUS = [
        self::pending,
        self::new,
        self::dispatched,
        self::delivered,
        self::reject,
        self::canceled,
        self::refunded,
    ];

    protected $fillable = [
        'store_id',
        'total_price',
        'payment_receipt',
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'order_id');
    }

    public function scopeForStore($query, $storeId)
    {
        return $query->whereHas('items', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        });
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
