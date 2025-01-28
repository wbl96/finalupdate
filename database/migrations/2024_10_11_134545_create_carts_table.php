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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->integer('total_items')->default(0);
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade');
        });

        // Migration for Cart Items table
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Store the price at the time of adding to cart
            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('carts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('cart_id');
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign('product_id');
        });;
        Schema::dropIfExists('carts');
    }
};
