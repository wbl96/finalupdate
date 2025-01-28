<?php

namespace App\Http\Controllers\Api\V1\Admins;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\UpdateContactSettingsRequest;
use App\Http\Requests\Admin\Settings\UpdateGeneralSettingsRequest;
use App\Http\Resources\ContactsResource;
use App\Http\Resources\SettingsResource;
use App\Interfaces\Admins\SiteSettingsRepositoryInterface;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private SiteSettingsRepositoryInterface $siteSettingsRepositoryInterface;

    public function __construct(SiteSettingsRepositoryInterface $siteSettingsRepositoryInterface)
    {
        $this->siteSettingsRepositoryInterface = $siteSettingsRepositoryInterface;
    }

    public function generalSettings()
    {
        $data = $this->siteSettingsRepositoryInterface->getGeneralSettings();
        return ApiResponseClass::sendResponse(new SettingsResource($data));
    }

    public function updateGeneralSettings(UpdateGeneralSettingsRequest $request)
    {
        // prepare data to store it
        $data = [
            'android_url' => $request->android_url,
            'android_version' => $request->android_version,
            'ios_url' => $request->ios_url,
            'ios_version' => $request->ios_version,
            'maintenance' => $request->maintenance,
            'keywords' => $request->keywords,
            'google_tag_head' => $request->google_tag_head,
            'google_tag_body' => $request->google_tag_body,
            'site_logo' => $request->site_logo,
            'site_name_ar' => $request->site_name_ar,
            'site_name_en' => $request->site_name_en,
        ];

        DB::beginTransaction();
        try {
            // store general settings data
            $this->siteSettingsRepositoryInterface->updateGeneralSettings($data);

            // check site logo
            if ($request->hasFile('site_logo')) {
                // get current logo
                $currentLogo = Setting::where('key', 'site_logo')->first();
                // delete it if exists
                if ($currentLogo && Storage::disk('public')->exists($currentLogo->value)) {
                    Storage::disk('public')->delete($currentLogo->value);
                }
                // get file
                $file = $request->file('site_logo');
                // save file into site_logo folder
                $path = $file->store('site_logo', 'public');
                // store it in database
                Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
            }
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('global.inserted'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    public function contactsSettings()
    {
        $data = $this->siteSettingsRepositoryInterface->getContactsSettings();
        return ApiResponseClass::sendResponse(new ContactsResource($data));
    }

    public function updateContactsSettings(UpdateContactSettingsRequest $request)
    {
        // prepare data to store it
        $data = [
            'email' => $request->email,
            'mobile' => $request->mobile,
            'whatsapp' => $request->whatsapp,
        ];

        DB::beginTransaction();
        try {
            // store faq data
            $faq = $this->siteSettingsRepositoryInterface->updateContactsSettings($data);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('global.inserted'));
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }
}
