<?php

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
        Schema::table('admins', function (Blueprint $table) {
            $table->text('fcm_token')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->text('fcm_token')->nullable();
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->text('fcm_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
    }
};
