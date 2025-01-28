<?php

namespace App\Classes;

use App\Models\Contact;

class Contacts
{
    protected $contacts;

    public function __construct()
    {
        // retrieve data as an array
        $this->contacts = Contact::all()->pluck('value', 'key')->toArray();
    }

    public function __get($key)
    {
        // retrieve setting as an attribute
        return $this->contacts[$key] ?? null;
    }

    public function __set($key, $value)
    {
        // assign new value
        $this->contacts[$key] = $value;
        // save new value
        Contact::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
