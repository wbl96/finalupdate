<?php

use App\Models\Contact;
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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // contacts keys
        $contacts = [
            ['key' => 'email'],
            ['key' => 'mobile'],
            ['key' => 'whatsapp'],
        ];

        // loop on contacts
        foreach ($contacts as $contact) {
            Contact::updateOrCreate(['key' => $contact['key']], $contact);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
