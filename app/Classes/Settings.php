<?php

namespace App\Classes;

use App\Models\Setting;

class Settings
{
    protected $settings;

    public function __construct()
    {
        // retrieve data as an array
        $this->settings = Setting::all()->pluck('value', 'key')->toArray();
    }

    public function __get($key)
    {
        // retrieve setting as an attribute
        return $this->settings[$key] ?? null;
    }

    public function __set($key, $value)
    {
        // assign new value
        $this->settings[$key] = $value;
        // save new value
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
