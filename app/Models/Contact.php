<?php

namespace App\Models;

use App\Traits\TimestampsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory, TimestampsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    public function getCreatedAtAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return !is_null($value) ? $this->formatDate($value) : null;
    }
}
