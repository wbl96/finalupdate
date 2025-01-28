<?php

namespace App\Repositories\Admins;

use App\Classes\Contacts;
use App\Classes\Settings;
use App\Interfaces\Admins\SiteSettingsRepositoryInterface;
use App\Models\Contact;
use App\Models\Setting;

class SiteSettingsRepository implements SiteSettingsRepositoryInterface
{
    public function getGeneralSettings()
    {
        $settings = new Settings();
        return $settings;
    }

    public function updateGeneralSettings(array $data)
    {
        // loop on validated request
        foreach ($data as $key => $setting) {
            if (!is_null($setting))
                Setting::updateOrCreate(['key' => $key], ['value' => $setting]);
        }

        return true;
    }

    public function getContactsSettings()
    {
        $contacts = new Contacts();
        return $contacts;
    }

    public function updateContactsSettings(array $data)
    {
        // loop on validated request
        foreach ($data as $key => $setting) {
            if (!is_null($setting))
                Contact::updateOrCreate(['key' => $key], ['value' => $setting]);
        }

        return true;
    }
}
