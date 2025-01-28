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
        Schema::create('products_sub_categories', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('category_id');
            $table->foreignid('category_id')->references('id')->on('products_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name_ar');
            $table->string('name_en');
            $table->foreignid('admin_id')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            // $table->unsignedBigInteger('admin_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_sub_categories');
    }
};
