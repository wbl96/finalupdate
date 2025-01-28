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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id'); 
            $table->unsignedBigInteger('store_id'); 
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('status', ['pending', 'new', 'in progress', 'received', 'refunded'])->default('pending');
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
