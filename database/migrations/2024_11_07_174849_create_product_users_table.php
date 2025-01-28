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
        Schema::create('products_detail_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('products_variant_id')->references('id')->on('products_details')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('min_order_qty')->default(1);
            $table->integer('max_order_qty')->nullable();
            $table->integer('qty');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_detail_user');
    }
};
