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
        Schema::create('rfq_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_item_id')->references('id')->on('cart_items')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('supplier_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->decimal('proposed_price', 10, 2);
            $table->integer('qty');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfq_requests');
    }
};
