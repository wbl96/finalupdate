<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['store_id', 'balance'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
} 