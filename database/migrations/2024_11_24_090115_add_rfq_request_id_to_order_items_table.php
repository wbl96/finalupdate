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
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('rfq_request_id')->after('id')->references('id')->on('rfq_requests')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('detail_key_id')->after('rfq_request_id')->references('id')->on('products_details_keys')->onUpdate('cascade')->onDelete('cascade');
            if (Schema::hasColumn('order_items',"product_id")) {
                $table->dropForeign(["product_id"]);
                $table->dropColumn('product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items',"rfq_request_id")) {
                $table->dropForeign(["rfq_request_id"]);
                $table->dropColumn('rfq_request_id');
            }
            if (Schema::hasColumn('order_items',"detail_key_id")) {
                $table->dropForeign(["detail_key_id"]);
                $table->dropColumn('detail_key_id');
            }
        });
    }
};
