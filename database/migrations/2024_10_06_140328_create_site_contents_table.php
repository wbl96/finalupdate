<?php

use App\Models\SiteContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->text('content');
            $table->timestamps();
        });

        // site sections
        $sections = [
            [
                'section' => 'terms',
                'content' => '',
            ],
            [
                'section' => 'privacy_policy',
                'content' => '',
            ],
            [
                'section' => 'about_us',
                'content' => '',
            ]
        ];

        // insert sections into DB
        SiteContent::insert($sections);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_contents');
    }
};
