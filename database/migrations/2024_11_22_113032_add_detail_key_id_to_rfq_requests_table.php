<?php

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
        DB::table('rfq_requests')->delete();
        Schema::table('rfq_requests', function (Blueprint $table) {
            $table->foreignId('detail_key_id')->after('cart_item_id')->references('id')->on('products_details_keys')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('store_id')->after('detail_key_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'closed'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfq_requests', function (Blueprint $table) {
            $table->dropForeign(["detail_key_id"]);
            $table->dropColumn('detail_key_id');
            $table->dropForeign(["store_id"]);
            $table->dropColumn('store_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
};
