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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cart_id')->after('id')->nullable()->constrained('carts')->onUpdate('cascade')->onDelete('set null');
            $table->string('payment_receipt')->after('total_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_receipt');
            $table->dropForeign(['cart_id']);
            $table->dropColumn('cart_id');
        });
    }
};
