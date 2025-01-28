<?php

use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile');
            $table->string('lng')->nullable();
            $table->string('lat')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('stores_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // prepare users
        $users = [
            [
                'name' => 'store',
                'email' => 'stores@stores.com',
                'password' => Hash::make('12345678'),
                'mobile' => '5485415',
                'lng' => '545412',
                'lat' => '4515'
            ],
        ];
        // insert users
        Store::insert($users);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
        Schema::dropIfExists('stores');
    }
};
