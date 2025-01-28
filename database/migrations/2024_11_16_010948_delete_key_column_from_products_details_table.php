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
        Schema::table('products_details', function (Blueprint $table) {
            $table->dropColumn('key');
        });
        Schema::table('products_details_keys', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_details', function (Blueprint $table) {
            $table->string('key')->nullable()->after('name');
        });
        Schema::table('products_details_keys', function (Blueprint $table) {
            $table->integer('qty')->nullable()->after('key');
        });
    }
};
