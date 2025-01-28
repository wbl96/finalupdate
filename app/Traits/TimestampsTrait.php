<?php

namespace App\Traits;

trait TimestampsTrait
{
    public function formatDate($value)
    {
        return date_format(date_create(preg_replace('/[Tt]/', ' ', $value)), 'Y-m-d h:i:sa');
    }
}
