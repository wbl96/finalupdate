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
        if (!Schema::hasColumn('products_detail_user', 'id')) {
            Schema::table('products_detail_user', function (Blueprint $table) {
                $table->id()->first();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_detail_user', function (Blueprint $table) {
            $table->dropIndex('id');
        });
    }
};
