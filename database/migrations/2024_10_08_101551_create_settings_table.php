<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // settings keys
        $settings = [
            ['key' => 'android_url'],
            ['key' => 'android_version'],
            ['key' => 'ios_url'],
            ['key' => 'ios_version'],
            ['key' => 'maintenance', 'value' => 'inactive'],
            ['key' => 'keywords'],
            ['key' => 'google_tag_head'],
            ['key' => 'google_tag_body'],
            ['key' => 'site_logo'],
            ['key' => 'site_name_ar'],
            ['key' => 'site_name_en'],
        ];

        // loop on settings
        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
