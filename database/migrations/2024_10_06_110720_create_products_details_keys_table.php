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
        Schema::create('products_details_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_detail_id')->nullable();
            $table->string('key');
            $table->string('qty');
            $table->timestamps();

            $table->foreign('products_detail_id')->references('id')->on('products_details')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_details_keys');
    }
};
