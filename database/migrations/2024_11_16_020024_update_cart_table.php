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
        Schema::table("carts", function (Blueprint $table) {
            $table->dropForeign(["supplier_id"]);
            $table->dropColumn('supplier_id');
            $table->dropColumn('total_price');
        });

        Schema::table("cart_items", function (Blueprint $table) {
            if (Schema::hasColumn("cart_items", "product_id")) {
                $table->dropForeign(["product_id"]);
                $table->dropColumn('product_id');
            }
            $table->foreignId('detail_key_id')->after('cart_id')->references('id')->on('products_details_keys')->onUpdate('cascade')->onDelete('cascade');

            $table->dropColumn('price');
            $table->renameColumn('quantity', 'qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("carts", function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable()->after('id');
            $table->decimal('total_price', 10, 2)->default(0)->after('store_id');

            $table->foreign('supplier_id')->references('id')->on('users')->onUpdate('cascade');
        });

        Schema::table("cart_items", function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->decimal('price', 10, 2); // Store the price at the time of adding to cart
            $table->renameColumn('aty', 'quantity');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
        });
    }
};
