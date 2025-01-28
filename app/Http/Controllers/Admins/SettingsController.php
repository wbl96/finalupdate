<?php

namespace App\Http\Controllers\Admins;

use App\Classes\Contacts;
use App\classes\Settings;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\UpdateContactSettingsRequest;
use App\Http\Requests\Admin\Settings\UpdateGeneralSettingsRequest;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * get general settings
     */
    public function generalSettings()
    {
        // get all settings using Settings class
        $settings = new Settings();
        // return view with settings
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * update general settings
     */
    public function updateGeneralSettings(UpdateGeneralSettingsRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // loop on validated request
            foreach ($request->safe()->except(['site_logo']) as $key => $setting) {
                Setting::updateOrCreate(['key' => $key], ['value' => $setting]);
            }
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
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.updated'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * get contact contacts
     */
    public function contactsSettings() {
        // get all contacts using Contacts class
        $contacts = new Contacts();
        // return view with contacts
        return view('admin.settings.contacts', compact('contacts'));
    }

    /**
     * update contact settings
     */
    public function updateContactsSettings(UpdateContactSettingsRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // loop on validated request
            foreach ($request->validated() as $key => $setting) {
                Contact::updateOrCreate(['key' => $key], ['value' => $setting]);
            }
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.updated'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }
}
