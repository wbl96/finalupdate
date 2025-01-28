<?php

use App\Models\Product;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->references('id')->on('users')->onUpdate('cascade')->nullOnDelete();
            $table->string('name_ar');
            $table->string('name_en');
            $table->unsignedBigInteger('category_id')->default(1);
            $table->foreignId('sub_category_id')->nullable()->references('id')->on('products_sub_categories')->onUpdate('cascade')->nullOnDelete();
            $table->text('description')->nullable();
            // $table->integer('min_order_qty')->default(1);
            // $table->integer('max_order_qty')->nullable();
            // $table->integer('qty')->nullable();
            // $table->string('payment_type')->nullable();
            // $table->string('delivery_method')->nullable();
            // $table->double('price');
            $table->enum('status', Product::$PRODUCT_STATUS)->default('new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
