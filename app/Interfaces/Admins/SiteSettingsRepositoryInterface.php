<?php

namespace App\Interfaces\Admins;

interface SiteSettingsRepositoryInterface
{
    public function getGeneralSettings();
    public function updateGeneralSettings(array $data);
    public function getContactsSettings();
    public function updateContactsSettings(array $data);
}
