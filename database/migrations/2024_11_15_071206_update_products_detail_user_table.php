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
        Schema::table('products_detail_user', function (Blueprint $table) {
            $table->dropColumn('max_order_qty');
            $table->dropForeign(['products_variant_id']);
            $table->dropColumn('products_variant_id');
            $table->foreignId('detail_key_id')->after('supplier_id')->references('id')->on('products_details_keys')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_detail_user', function (Blueprint $table) {
            $table->integer('max_order_qty')->nullable()->after('min_order_qty');
            $table->dropForeign(['detail_key_id']);
            $table->dropColumn('detail_key_id');

            $table->foreignId('products_variant_id')->after('supplier_id')->references('id')->on('products_details')->onUpdate('cascade')->onDelete('cascade');
        });
    }
};
